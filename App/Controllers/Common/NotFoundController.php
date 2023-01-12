<?php

namespace App\Controllers\Common;

use System\Controller;

class NotFoundController extends Controller
{
    public function index()
    {
        return $this->view->render('common/not-found');
    }
}