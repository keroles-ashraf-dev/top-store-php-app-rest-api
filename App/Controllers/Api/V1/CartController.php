<?php

namespace App\Controllers\Api\V1;

use System\Controller;

class CartController extends Controller
{
    /**
     * get user cart data
     *
     * @return mixed
     */
    public function index()
    {
        $userId = $this->request->get('user-id');

        if (!is_numeric($userId)) {

            $res['success'] = 0;
            $res['message'] = 'User id is invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $cartModel = $this->load->model('Cart');
        $settingsModel = $this->load->model('Settings');

        $products = $cartModel->getCartProducts($userId);
        if (empty($products)) {

            $data['items'] = $products;
            $data['subtotal'] = 0.0;
            $data['shipping'] = 0.0;
            $data['vat'] = 0.0;
            $data['total'] = 0.0;

            $res['success'] = 1;
            $res['message'] = 'Getting cart successfully';
            $res['data']['cart'] = $data;

            $this->api->setHeaders()->success($res);
        }
        $subtotal = $this->subtotalPrice($products);
        $shipping = $settingsModel->get('shipping');
        $vatPercent = $settingsModel->get('vat');
        $vat = ($vatPercent * $subtotal) / 100;

        foreach ($products as $p) {
            $image = $p->image;

            unset($p->image);

            $p->images[] = assets('common/images/' . $image);
        }

        $data['items'] = $products;
        $data['subtotal'] = floatval($subtotal);
        $data['shipping'] = floatval($shipping);
        $data['vat'] = floatval(bcdiv($vat, '1', 2));
        $data['total'] = floatval(bcdiv($subtotal + $shipping + $vat, '1', 2));

        $res['success'] = 1;
        $res['message'] = 'Getting cart successfully';
        $res['data']['cart'] = $data;

        $this->api->setHeaders()->success($res);
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
     * increment cart item
     *
     * @return mixed
     */
    public function increment()
    {
        $userId = $this->request->fileGetContents('user-id');
        $productId = $this->request->fileGetContents('product-id');

        if (!is_numeric($userId) || !is_numeric($productId)) {

            $res['success'] = 0;
            $res['message'] = 'Some params are invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $cartModel = $this->load->model('Cart');

        $cartModel->increment($userId, $productId);

        $settingsModel = $this->load->model('Settings');

        $products = $cartModel->getCartProducts($userId);
        $subtotal = $this->subtotalPrice($products);
        $shipping = $settingsModel->get('shipping');
        $vatPercent = $settingsModel->get('vat');
        $vat = ($vatPercent * $subtotal) / 100;

        foreach ($products as $p) {
            $image = $p->image;

            unset($p->image);

            $p->images[] = assets('common/images/' . $image);
        }

        $data['items'] = $products;
        $data['subtotal'] = floatval($subtotal);
        $data['shipping'] = floatval($shipping);
        $data['vat'] = floatval(bcdiv($vat, '1', 2));
        $data['total'] = floatval(bcdiv($subtotal + $shipping + $vat, '1', 2));

        $res['success'] = 1;
        $res['message'] = 'Getting cart successfully';
        $res['data']['cart'] = $data;

        $this->api->setHeaders()->success($res);
    }

    /**
     * decrement cart item
     *
     * @return mixed
     */
    public function decrement()
    {
        $userId = $this->request->fileGetContents('user-id');
        $productId = $this->request->fileGetContents('product-id');

        if (!is_numeric($userId) || !is_numeric($productId)) {

            $res['success'] = 0;
            $res['message'] = 'Some params are invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $cartModel = $this->load->model('Cart');

        $cartModel->decrement($userId, $productId);

        $settingsModel = $this->load->model('Settings');

        $products = $cartModel->getCartProducts($userId);

        if (empty($products)) {

            $data['items'] = $products;
            $data['subtotal'] = 0.0;
            $data['shipping'] = 0.0;
            $data['vat'] = 0.0;
            $data['total'] = 0.0;

            $res['success'] = 1;
            $res['message'] = 'Getting cart successfully';
            $res['data']['cart'] = $data;

            $this->api->setHeaders()->success($res);
        }
        
        $subtotal = $this->subtotalPrice($products);
        $shipping = $settingsModel->get('shipping');
        $vatPercent = $settingsModel->get('vat');
        $vat = ($vatPercent * $subtotal) / 100;

        foreach ($products as $p) {
            $image = $p->image;

            unset($p->image);

            $p->images[] = assets('common/images/' . $image);
        }

        $data['items'] = $products;
        $data['subtotal'] = floatval($subtotal);
        $data['shipping'] = floatval($shipping);
        $data['vat'] = floatval(bcdiv($vat, '1', 2));
        $data['total'] = floatval(bcdiv($subtotal + $shipping + $vat, '1', 2));

        $res['success'] = 1;
        $res['message'] = 'Getting cart successfully';
        $res['data']['cart'] = $data;

        $this->api->setHeaders()->success($res);
    }

    /**
     * add cart item
     *
     * @return mixed
     */
    public function add()
    {
        $userId = $this->request->fileGetContents('user-id');
        $productId = $this->request->fileGetContents('product-id');

        if (!is_numeric($userId) || !is_numeric($productId)) {

            $res['success'] = 0;
            $res['message'] = 'Some params are invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $productsModel = $this->load->model('Products');

        if (!$productsModel->exists($productId)) {

            $res['success'] = 0;
            $res['message'] = 'Product are not exist';

            $this->api->setHeaders()->notFound($res);
        }

        $cartModel = $this->load->model('Cart');

        $success = $cartModel->add($userId, $productId);

        if (!$success) {
            // it means there are errors during update database
            $res['success'] = 0;
            $res['message'] = 'Something wrong happens try again later';

            $this->api->setHeaders()->internalError($res);
        }

        $res['success'] = 1;
        $res['message'] = 'Added to cart successfully';

        $this->api->setHeaders()->success($res);
    }
}
