<?php

namespace App\Controllers\Api\V1;

use System\Controller;

class ProductsController extends Controller
{
    /**
     * Product
     *
     * @return mixed
     */
    public function index()
    {
        $productId = $this->request->get('id');


        if (!is_numeric($productId)) {
            $res['success'] = 0;
            $res['message'] = 'Product id is invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $productModel = $this->load->model('Products');

        $product = $productModel->get($productId);

        $images = $productModel->getProductImages($product->id);
        $count = count($images);

        for ($i = 0; $i < $count; $i++) {
            $images[$i] = assets('common/images/' . $images[$i]->name);
        }

        $product->images = $images;

        $res['success'] = 1;
        $res['message'] = 'Getting product successfully';
        $res['data']['product'] = $product;

        $this->api->setHeaders()->success($res);
    }
}
