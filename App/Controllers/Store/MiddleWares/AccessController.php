<?php

namespace App\Controllers\Store\MiddleWares;

use System\Controller;

class AccessController extends Controller
{
    /**
     * Check User Permissions to access admin pages
     *
     * @return void
     */
    public function index()
    {
        $loginModel = $this->load->model('Login');

        $isNotLogged =  !$loginModel->isLogged();

        // User is not logged in so redirect him to login page
        if ($isNotLogged) {
            return $this->url->redirectTo('/login');
        }
    }
}
