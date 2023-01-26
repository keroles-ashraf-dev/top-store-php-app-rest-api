<?php

namespace App\Controllers\Store;

use System\Controller;

class OrdersController extends Controller
{
    /**
     * Display product page
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Orders');

        $loginModel = $this->load->model('Login');
        $ordersModel = $this->load->model('Orders');

        $orders = $ordersModel->getOrders($loginModel->user()->id);

        $data['orders'] = $orders;

        $view = $this->view->render('store/profile/orders/orders', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * Submit for add product to cart
     *
     * @return string | json
     */
    public function create()
    {
        $loginModel = $this->load->model('Login');
        $user = $loginModel->user();

        if (!is_numeric($user->default_address_id)) {
            $json['redirectTo'] = $this->url->link('/profile/addresses');
            return json_encode($json);
        }
        $cartModel = $this->load->model('Cart');
        $settingsModel = $this->load->model('Settings');

        $products = $cartModel->getCartProducts($user->id);

        $subtotalPrice = $this->subtotalPrice($products);
        $vat = (($settingsModel->get('vat')) * $subtotalPrice) / 100;
        $shipping = $settingsModel->get('shipping');
        $total = $subtotalPrice + $vat + $shipping;
        $paymentType = $this->request->post('payment-method');

        $orderData['items'] = $products;
        $orderData['user-id'] = $user->id;
        $orderData['total'] = $total;
        $orderData['subtotal'] = $subtotalPrice;
        $orderData['shipping'] = $shipping;
        $orderData['vat'] = $vat;
        $orderData['status'] = 'processing';
        $orderData['payment-type'] = $paymentType;
        $orderData['address-id'] = $user->default_address_id;
        $orderData['payment-status'] = 'unpaid';
        $orderData['created'] = time();

        $ordersModel = $this->load->model('Orders');

        $orderId = $ordersModel->create($orderData);
        $orderData['id'] = $orderId;

        $cartModel->clearUserCart($user->id);

        $json['success'] = true;
        $json['message'] = 'Order placed successfully';

        if ($paymentType == 'cash') {
            $json['redirectTo'] = $this->url->link('/profile/orders');
        } else {
            $this->app->session->set('order', $orderData);
            $json['redirectTo'] = $this->url->link('/payment');
        }

        return json_encode($json);
    }

    /**
     * calculate products subtotal price
     *
     * @return string
     */
    public function subtotalPrice($products)
    {
        $total = 0;

        foreach ($products as $product) {

            $productTotal = 0;

            if (empty($product->discounted_price)) {
                $productTotal = ($product->price * $product->count);
            } else {
                $productTotal = ($product->discounted_price * $product->count);
            }

            $total = $total + $productTotal;
        }

        return bcdiv($total, '1', 2);
    }

    /**
     * cancel order
     *
     * @return string
     */
    public function cancel()
    {
        $orderId = $this->request->post('id');

        if (!is_numeric($orderId)) {

            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $ordersModel = $this->load->model('Orders');

        if (!$ordersModel->exists($orderId)) {

            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $ordersModel->updateStatus($orderId, 'canceled');

        $json['success'] = true;
        $json['message'] = 'Order canceled successfully';
        $json['orderId'] = $orderId;
        return json_encode($json);
    }
}
