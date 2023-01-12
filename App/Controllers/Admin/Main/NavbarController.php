<?php

namespace App\Controllers\Admin\Main;

use System\Controller;

class NavbarController extends Controller
{
    public function index()
    {
        $data['user'] = $this->load->model('Login')->user();

        $data['languages'] = $this->load->model('Languages')->getEnabledLanguages();

        return $this->view->render('admin/main/navbar', $data);
    }
}
