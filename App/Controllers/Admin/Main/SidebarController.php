<?php

namespace App\Controllers\Admin\Main;

use System\Controller;

class SidebarController extends Controller
{
    public function index()
    {
        // send current path
        $data['path'] = $this->request->url();

        return $this->view->render('admin/main/sidebar', $data);
    }
}
