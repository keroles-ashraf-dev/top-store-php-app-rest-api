<?php

namespace App\Controllers\Admin\Main;

use System\Controller;

class HeaderController extends Controller
{
    public function index()
    {
        $data['title'] = $this->html->getTitle();

        // send current path
        $data['path'] = $this->request->url();

        return $this->view->render('admin/main/header', $data);
    }
}