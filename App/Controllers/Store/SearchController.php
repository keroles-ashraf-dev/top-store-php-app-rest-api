<?php

namespace App\Controllers\Store;

use System\Controller;

class SearchController extends Controller
{
    /**
     * Display product page
     *
     * @return mixed
     */
    public function index()
    {

        $categoryId = $this->request->get('category-id');
        $keyword = $this->request->get('keyword');

        if (($categoryId != 'null' && !is_numeric($categoryId)) || !preg_match('/^[a-zA-Z0-9 ]*$/', $keyword)) {
            return $this->url->redirectTo('/404');
        }

        $sortBy = $this->request->get('sort-by', 'name');
        $orderBy = $this->request->get('order-by', 'ASC');

        $sorters = ['name', 'price', 'rating'];
        $orders = ['ASC', 'DESC'];

        if (!in_array($sortBy, $sorters) || !in_array($orderBy, $orders)) {
            return $this->url->redirectTo('/404');
        }

        if ($categoryId != null) {
            $categoriesModel = $this->load->model('Categories');
            $data['subCategories'] = $categoriesModel->getSubCategoriesOfCategory($categoryId);
        }
        
        $productsModel = $this->load->model('Products');
        $data['products'] = $productsModel->paginateSearchedProducts($categoryId, $keyword, $orderBy, $sortBy);
        
        $data['pagination'] = $this->pagination->paginate();
        $data['keyword'] = $keyword;
        $data['categoryId'] = $categoryId;
        $data['orderBy'] = $orderBy;
        $data['sortBy'] = $sortBy;
        
        $this->html->setTitle('Search');
        $view = $this->view->render('store/search/search', $data);

        return $this->storeLayout->render($view);
    }
}
