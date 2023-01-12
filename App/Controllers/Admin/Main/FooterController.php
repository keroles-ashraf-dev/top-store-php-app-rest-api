<?php

namespace App\Controllers\Admin\Main;

use System\Controller;

class FooterController extends Controller
{
    public function index()
    {
        // send current path
        $data['path'] = $this->request->url();

        return $this->view->render('admin/main/footer', $data);
    }
}
