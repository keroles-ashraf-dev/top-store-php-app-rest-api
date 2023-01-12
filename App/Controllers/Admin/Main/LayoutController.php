<?php

namespace App\Controllers\Admin\Main;

use System\Controller;
use System\View\ViewInterface;

class LayoutController extends Controller
{
    /**
    * Render the layout with the given view Object
    *
    * @param \System\View\ViewInterface $view
    */
    public function render(ViewInterface $view)
    {
        $data['header'] = $this->load->controller('Admin/Main/Header')->index();
        $data['navbar'] = $this->load->controller('Admin/Main/Navbar')->index();
        $data['sidebar'] = $this->load->controller('Admin/Main/Sidebar')->index();
        $data['content'] = $view;
        $data['footer'] = $this->load->controller('Admin/Main/Footer')->index();

        return $this->view->render('admin/main/layout', $data);
    }
}