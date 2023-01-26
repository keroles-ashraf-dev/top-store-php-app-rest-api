<?php

namespace App\Controllers\Store;

use System\Controller;

class PaymentController extends Controller
{
    /**
     * Display product page
     *
     * @return mixed
     */
    public function index()
    {
        $data = $this->app->file->import('/config/paymob.php');

        $apiKey = $data['paymentApiKey'];
        $authToken = $this->getAuthToken($apiKey);
        $orderId = $this->createOrder($authToken);
        $paymentToken = $this->getPaymentKey($authToken, $orderId);
        return $this->redirectToPay($paymentToken);
    }

    /**
     * send request to create order
     *
     * @return String
     */
    public function createOrder($authToken)
    {
        $url = 'https://accept.paymob.com/api/ecommerce/orders';
        $postHeaders = array("Content-Type:application/json", "Accept:application/json");
        $postData = array(
            "auth_token" => $authToken,
            "delivery_needed" => "false",
            "amount_cents" => intval($_SESSION['order']['total']),
            "items" => array(),
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $postHeaders);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);


        return $data['id'];
    }

    /**
     * send request to get payment key
     *
     * @return String
     */
    public function getPaymentKey($authToken, $orderId)
    {
        $user = $this->load->model('Login')->user();

        $url = 'https://accept.paymob.com/api/acceptance/payment_keys';
        $postHeaders = array("Content-Type:application/json", "Accept:application/json");
        $postData = array(
            "auth_token" => $authToken,
            "amount_cents" => intval($_SESSION['order']['total']),
            "expiration" => 3600,
            "order_id" => $orderId,
            "billing_data" => array(
                "apartment" => "NA",
                "email" => $user->email,
                "floor" => "NA",
                "first_name" => $user->first_name,
                "street" => "NA",
                "building" => "NA",
                "phone_number" => $user->phone,
                "shipping_method" => "NA",
                "postal_code" => "NA",
                "city" => "NA",
                "country" => "NA",
                "last_name" => $user->last_name,
                "state" => "NA",
            ),
            "currency" => "EGP",
            "integration_id" => 3139072,
            "lock_order_when_paid" => false,
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $postHeaders);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        return $data['token'];
    }

    /**
     * send request to redirect tp iframe to pay
     *
     * @return String
     */
    public function redirectToPay($paymentToken)
    {
        $url = 'https://accept.paymob.com/api/acceptance/iframes/705802?payment_token=' . $paymentToken;

        $this->html->setTitle('Pay');

        $data['iframeUrl'] = $url;

        $view = $this->view->render('store/payment/payment', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * check payment status
     *
     * @return String
     */
    public function checkPaymentStatus()
    {
        return $this->url->redirectTo('/profile/orders');

        $paymentId = $this->request->get('id');
        $orderId = intval($_SESSION['order']['id']);

        if (!is_numeric($paymentId) || !is_numeric($orderId)) return $this->url->redirectTo('/404');

        $ordersModel = $this->load->model('Orders');

        if (!$ordersModel->exists($orderId))  return $this->url->redirectTo('/404');


        $success = $this->getTransactionData($paymentId);

        if ($success) {
            $ordersModel->updatePaymentData($orderId, 'processing', $paymentId, 'paid');
        } else {
            $ordersModel->updatePaymentData($orderId, 'canceled', $paymentId, 'unpaid');
        }

        $this->session->remove('order');
        return $this->url->redirectTo('/profile/orders');
    }

    /**
     * check payment status
     *
     * @return String
     */
    public function getTransactionData($id)
    {
        $data = $this->app->file->import('/config/paymob.php');

        $apiKey = $data['paymentApiKey'];
        $token = $this->getAuthToken($apiKey);

        $url = 'https://accept.paymob.com/api/acceptance/transactions/' . $id;
        $postHeaders = array(
            "Content-Type:application/json",
            "Accept:application/json",
            "authorization:Bearer $token",
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $postHeaders);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        return $data['success'];
    }

    /**
     * send request to retrieve auth token
     *
     * @return String
     */
    public function getAuthToken($apiKey)
    {
        $url = 'https://accept.paymob.com/api/auth/tokens';
        $postHeaders = array("Content-Type:application/json", "Accept:application/json");
        $postData = array("api_key" => $apiKey);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $postHeaders);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        return $data['token'];
    }
}
