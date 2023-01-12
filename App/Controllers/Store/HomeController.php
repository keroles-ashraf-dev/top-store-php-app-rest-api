<?php

namespace App\Controllers\Store;

use System\Controller;

class HomeController extends Controller
{
    /**
     * Display Home Page
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle(ucfirst($this->settings->get('site_name')));

        $offersModel = $this->load->model('Offers');
        $categoriesModel = $this->load->model('Categories');

        $data['sliderImages'] = $offersModel->getEnabledSliderImages();
        $data['deals'] = $offersModel->getEnabledDeals();
        $data['subCategories'] = $categoriesModel->getEnabledSubCategories();

        $view = $this->view->render('store/home/home', $data);

        return $this->storeLayout->render($view);
    }
}
