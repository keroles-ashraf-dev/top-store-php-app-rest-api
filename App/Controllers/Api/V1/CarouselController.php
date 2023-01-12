<?php

namespace App\Controllers\Api\V1;

use System\Controller;

class CarouselController extends Controller
{
    /**
     * return Carousel items
     *
     * @return mixed
     */
    public function index()
    {
        $offersModel = $this->load->model('Offers');

        $carousel = $offersModel->getEnabledSliderImages();

        foreach ($carousel as $c) {
            $c->image = assets('common/images/' . $c->image);
        }

        $res['success'] = 1;
        $res['message'] = 'Getting carousel successfully';
        $res['data']['carousel'] = $carousel;

        $this->api->setHeaders()->success($res);
    }
}
