<?php

namespace App\Controllers\Store;

use System\Controller;

class CategoriesController extends Controller
{
    /**
     * Display product page
     *
     * @return mixed
     */
    public function index()
    {

        $categoryId = $this->request->get('id');

        if (!is_numeric($categoryId)) {
            return $this->url->redirectTo('/404');
        }

        $sortBy = $this->request->get('sort-by', 'name');
        $orderBy = $this->request->get('order-by', 'ASC');

        $sorters = ['name', 'price', 'rating'];
        $orders = ['ASC', 'DESC'];

        if (!in_array($sortBy, $sorters) || !in_array($orderBy, $orders)) {
            return $this->url->redirectTo('/404');
        }

        $this->html->setTitle('Category');

        $categoriesModel = $this->load->model('Categories');
        $productsModel = $this->load->model('Products');

        $data['subCategories'] = $categoriesModel->getSubCategoriesOfCategory($categoryId);
        $data['products'] = $productsModel->paginateCategoryProducts($categoryId, $orderBy, $sortBy);

        // pred($data['products']);
        $data['pagination'] = $this->pagination->paginate();

        $data['categoryId'] = $categoryId;

        $data['sortBy'] = $sortBy;
        $data['orderBy'] = $orderBy;

        $view = $this->view->render('store/category/category', $data);

        return $this->storeLayout->render($view);
    }
}
