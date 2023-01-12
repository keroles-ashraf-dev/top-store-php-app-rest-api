<?php

namespace App\Controllers\Store;

use System\Controller;

class CartController extends Controller
{
    /**
     * Display product page
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Cart');
        
        $loginModel = $this->load->model('Login');
        $cartModel = $this->load->model('Cart');
        $settingsModel = $this->load->model('Settings');

        $products = $cartModel->getCartProducts($loginModel->user()->id);
        $subtotalPrice = $this->subtotalPrice($products);
        $vatPercent = $settingsModel->get('vat');
        $shipping = $settingsModel->get('shipping');

        $data['products'] = $products;
        $data['subtotalPrice'] = $subtotalPrice;
        $data['vat'] = ($vatPercent * $subtotalPrice) / 100;
        $data['vatPercent'] = $vatPercent;
        $data['shipping'] = $shipping;

        $view = $this->view->render('store/cart/cart', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * calculate products subtotal price
     *
     * @return string
     */
    public function subtotalPrice($products = null)
    {
        if ($products == null) {
            $loginModel = $this->load->model('Login');
            $cartModel = $this->load->model('Cart');
            $products = $cartModel->getCartProducts($loginModel->user()->id);
        }
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
     * Submit for add product to cart
     *
     * @return string | json
     */
    public function add()
    {
        $loginModel = $this->load->model('Login');

        if (!$loginModel->isLogged()) {
            $json['success'] = false;
            $json['message'] = 'You have to login';
            $json['redirectTo'] = url('/login');
            return json_encode($json);
        }

        $productId = $this->request->post('id');

        if (!is_numeric($productId)) {

            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $productsModel = $this->load->model('Products');

        if (!$productsModel->exists($productId)) {

            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $cartModel = $this->load->model('Cart');

        $userId = $loginModel->user()->id;

        $success = $cartModel->add($userId, $productId);

        if (!$success) {
            // it means there are errors during update database
            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $count = $cartModel->cartCount($userId);

        if (!is_numeric($count)) {

            $json['success'] = true;
            $json['message'] = 'Product added successfully';
            $json['cartCount'] = 0;
            return json_encode($json);
        }

        $json['success'] = true;
        $json['message'] = 'Product added successfully';
        $json['cartCount'] = $count;

        return json_encode($json);
    }

    /**
     * Submit for increment product in cart
     *
     * @return string | json
     */
    public function increment()
    {
        $loginModel = $this->load->model('Login');

        if (!$loginModel->isLogged()) {
            $json['success'] = false;
            $json['message'] = 'You have to login';
            $json['redirectTo'] = url('/login');
            return json_encode($json);
        }

        $productId = $this->request->post('id');

        if (!is_numeric($productId)) {

            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $productsModel = $this->load->model('Products');

        if (!$productsModel->exists($productId)) {

            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $cartModel = $this->load->model('Cart');

        $userId = $loginModel->user()->id;

        $success = $cartModel->increment($userId, $productId);

        if (!$success) {
            // it means there are errors during update database
            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $count = $cartModel->cartCount($userId);

        if (!is_numeric($count)) {

            $json['success'] = true;
            $json['message'] = 'Product added successfully';
            $json['cartCount'] = 0;
            return json_encode($json);
        }

        $json['success'] = true;
        $json['increment'] = true;
        $json['productId'] = $productId;
        $json['cartCount'] = $count;
        $json['subtotalPrice'] = $this->subtotalPrice();

        return json_encode($json);
    }

    /**
     * Submit for decrement product in cart
     *
     * @return string | json
     */
    public function decrement()
    {
        $loginModel = $this->load->model('Login');

        if (!$loginModel->isLogged()) {
            $json['success'] = false;
            $json['message'] = 'You have to login';
            $json['redirectTo'] = url('/login');
            return json_encode($json);
        }

        $productId = $this->request->post('id');

        if (!is_numeric($productId)) {

            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $productsModel = $this->load->model('Products');

        if (!$productsModel->exists($productId)) {

            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $cartModel = $this->load->model('Cart');

        $userId = $loginModel->user()->id;

        $success = $cartModel->decrement($userId, $productId);

        if (!$success) {
            // it means there are errors during update database
            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $count = $cartModel->cartCount($userId);

        if (!is_numeric($count)) {

            $json['success'] = true;
            $json['message'] = 'Product added successfully';
            $json['cartCount'] = 0;
            return json_encode($json);
        }

        $json['success'] = true;
        $json['decrement'] = true;
        $json['productId'] = $productId;
        $json['cartCount'] = $count;
        $json['subtotalPrice'] = $this->subtotalPrice();

        return json_encode($json);
    }
}
