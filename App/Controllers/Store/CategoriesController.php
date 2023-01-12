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
        
        $orderBy = $this->request->get('order-by', 'name');
        $sortBy = $this->request->get('sort-by', 'ASC');
        
        $this->html->setTitle('Category');

        $categoriesModel = $this->load->model('Categories');
        $productsModel = $this->load->model('Products');

        $data['subCategories'] = $categoriesModel->getSubCategoriesOfCategory($categoryId);
        $data['products'] = $productsModel->paginateCategoryProducts($categoryId, $orderBy, $sortBy);

       // pred($data['products']);
        $data['pagination'] = $this->pagination->paginate();

        $data['categoryId'] = $categoryId;
        
        $data['orderBy'] = $orderBy;
        $data['sortBy'] = $sortBy;

        $view = $this->view->render('store/category/category', $data);

        return $this->storeLayout->render($view);
    }
}
