<?php

namespace App\Controllers\Api\V1;

use System\Controller;

class CategoriesController extends Controller
{
    /**
     * return main categories
     *
     * @return mixed
     */
    public function index()
    {
        $categoriesModel = $this->load->model('Categories');

        $categories = $categoriesModel->getEnabledParentCategories();

        foreach ($categories as $c) {
            $c->image = assets('common/images/' . $c->image);
        }

        $res['success'] = 1;
        $res['message'] = 'Getting categories successfully';
        $res['data']['categories'] = $categories;

        $this->api->setHeaders()->success($res);
    }

    /**
     * get category products
     *
     * @return mixed
     */
    public function getCategoryProducts()
    {

        $categoryId = $this->request->get('category-id');
        $pagination = $this->request->get('pagination');

        $itemsCount = $pagination['items-count'];
        $offset = $pagination['offset'];

        if (!is_numeric($categoryId) || !is_numeric($itemsCount) || !is_numeric($offset)) {
            $res['success'] = 0;
            $res['message'] = 'Some Request params is invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $orderBy = $this->request->get('order-by', 'name');
        $sortBy = $this->request->get('sort-by', 'ASC');

        $productsModel = $this->load->model('Products');

        $products = $productsModel->paginateCategoryProducts($categoryId, $orderBy, $sortBy, $itemsCount, $offset);

        foreach ($products as $p) {
            $image = $p->image;

            unset($p->image);

            $p->images[] = assets('common/images/' . $image);
        }

        $res['success'] = 1;
        $res['message'] = 'Getting category products successfully';
        $res['data']['products'] = $products;

        $this->api->setHeaders()->success($res);
    }
}
