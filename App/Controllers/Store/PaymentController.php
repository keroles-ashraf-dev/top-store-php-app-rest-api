<?php

namespace App\Controllers\Store;

use System\Controller;

class PaymentController extends Controller
{
    /**
     * paymob api key
     * 
     * @var string
     */
    private $apiKey = '';

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
    }

    /**
     * send request to retrieve auth token
     *
     * @return String
     */
    public function getAuthToken($apiKey)
    {
        $url = 'https://accept.paymob.com/api/auth/tokens';
        $data = array("api_key" => $apiKey);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response['token'];
    }

    /**
     * send request to create order
     *
     * @return String
     */
    public function createOrder($authToken)
    {
        $url = 'https://accept.paymob.com/api/ecommerce/orders';
        $data = array(
            "auth_token" => $authToken,
            "delivery_needed" => "false",
            "amount_cents" => $_SESSION['order']['total'],
            "items" => array(),
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response['id'];
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
        $data = array(
            "auth_token" => $authToken,
            "amount_cents" => $_SESSION['order']['total'],
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
            "integration_id" => 1,
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response['token'];
    }

    /**
     * send request to redirect tp iframe to pay
     *
     * @return String
     */
    public function redirectToPay($paymentToken)
    {
        $url = 'https://accept.paymob.com/api/acceptance/iframes/705802?payment_token=' . $paymentToken;
        
        header('location:' . $url);
    }
}
