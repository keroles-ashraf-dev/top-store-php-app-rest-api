<?php

namespace App\Controllers\Store\Main;

use System\Controller;

class HeaderController extends Controller
{
    public function index()
    {
        $data['title'] = $this->html->getTitle();

        // send current path
        $data['path'] = $this->request->url();

        return $this->view->render('store/main/header', $data)->getOutput();
    }
}
