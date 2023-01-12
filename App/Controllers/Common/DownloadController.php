<?php

namespace App\Controllers\Common;

use System\Controller;

class DownloadController extends Controller
{
    /**
     * Display Registration Page
     *
     * @return mixed
     */
    public function index()
    {

        $loginModel = $this->load->model('Login');

        if (!$loginModel->isLogged()) {
            return $this->url->redirectTo('/404');
        }

        $user = $loginModel->user();

        if ($user->role !== 'admin') {
            return $this->url->redirectTo('/');
        }

        $file = $_GET['file'];

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $file . "");
        header("Content-Transfer-Encoding: binary");
        header("Content-Type: binary/octet-stream");

        readfile(assets('common/i18n/' . $file));
    }
}
