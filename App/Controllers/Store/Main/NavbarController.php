<?php

namespace App\Controllers\Store\Main;

use System\Controller;

class NavbarController extends Controller
{
    public function index()
    {
        $categoryId = $this->request->get('category-id');
        $keyword = $this->request->get('keyword');

        $data['keyword'] = $keyword;
        $data['categoryId'] = $categoryId;
        $data['categories'] = $this->load->model('Categories')->getEnabledCategories();
        $data['parentCategories'] = $this->load->model('Categories')->getEnabledParentCategories();
        $data['languages'] = $this->load->model('Languages')->getEnabledLanguages();
        $data['navAnnouncement'] = $this->load->model('Settings')->get('nav_announcement');
        $data['cartCount'] = 0;

        $loginModel = $this->load->model('Login');

        if ($loginModel->isLogged()) {
            $user = $loginModel->user();
            $data['user'] = $user;
            $userId = $loginModel->user()->id;
            $data['cartCount'] = $this->load->model('Cart')->cartCount($userId);

            if($user->default_address_id == null || empty($user->default_address_id)){
                $data['address'] = $this->security->getUserCountry();
            }else{
                $data['address'] = $this->load->model('Addresses')->getAddress($user->default_address_id);
            }
            
        } else {
            $data['address'] = $this->security->getUserCountry();
        }

        return $this->view->render('store/main/navbar', $data);
    }
}
