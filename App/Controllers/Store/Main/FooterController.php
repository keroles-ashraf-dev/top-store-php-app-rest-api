<?php

namespace App\Controllers\Store\Main;

use System\Controller;

class FooterController extends Controller
{
    public function index()
    {
        // send current path
        $data['path'] = $this->request->url();

        return $this->view->render('store/main/footer', $data);
    }
}
