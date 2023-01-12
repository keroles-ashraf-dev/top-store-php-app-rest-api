<?php

namespace App\Controllers\Store;

use System\Controller;

class ProductsController extends Controller
{
    /**
     * Display product page
     *
     * @return mixed
     */
    public function index()
    {
        
        $productId = $this->request->get('id');
        
        if (!is_numeric($productId)) {
            return $this->url->redirectTo('/404');
        }
        
        $this->html->setTitle('Product');

        $productModel = $this->load->model('Products');
        $categoriesModel = $this->load->model('Categories');

        $productData = $productModel->get($productId);

        if(empty($productData)) return $this->url->redirectTo('/404');

        $data['product'] = $productData;
        $data['images'] = $productModel->getProductImages($productId);
        // product parent categories
        $data['categories'] = $categoriesModel->getProductParentCategories($productId);
        // product related products
        $data['relatedProducts'] = $productModel->getProductRelatedProducts($productId);

        $view = $this->view->render('store/product/product', $data);

        return $this->storeLayout->render($view);
    }
}
