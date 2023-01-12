<?php

namespace App\Controllers\Store;

use System\Controller;

class ProfileController extends Controller
{
    /**
     * Display profile page
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Profile');

        $view = $this->view->render('store/profile/profile');

        return $this->storeLayout->render($view);
    }
}
