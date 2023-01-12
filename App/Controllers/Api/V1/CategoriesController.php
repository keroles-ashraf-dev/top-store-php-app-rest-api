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
}
