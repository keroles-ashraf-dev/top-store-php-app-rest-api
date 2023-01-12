<?php

namespace App\Controllers\Admin\MiddleWares;

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

        // First Scenario :
        // User is not logged in so redirect him to login page
        if ($isNotLogged) {
            return $this->url->redirectTo('/login');
        }

        // Second Scenario :
        // user is logged in successfully and he is requesting an admin page
        $user = $loginModel->user();

        if ($user->role === 'admin') {
            return;
        } else {
            return $this->url->redirectTo('/');
        }
    }
}
