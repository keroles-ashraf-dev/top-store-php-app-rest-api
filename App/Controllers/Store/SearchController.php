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

        if (($categoryId != 'null' && !is_numeric($categoryId)) || empty($keyword)) {
            return $this->url->redirectTo('/404');
        }

        $orderBy = $this->request->get('order-by', 'name');
        $sortBy = $this->request->get('sort-by', 'ASC');

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
