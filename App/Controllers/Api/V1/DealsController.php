<?php

namespace App\Controllers\Api\V1;

use System\Controller;

class DealsController extends Controller
{
    /**
     * Deals
     *
     * @return mixed
     */
    public function index()
    {
        $offersModel = $this->load->model('Offers');

        $deals = $offersModel->getEnabledDeals();

        foreach ($deals as $c) {
            $image = $c->image;

            unset($c->image);

            $c->images[] = assets('common/images/' . $image);
        }

        $res['success'] = 1;
        $res['message'] = 'Getting deals successfully';
        $res['data']['deals'] = $deals;

        $this->api->setHeaders()->success($res);
    }

    /**
     * get deal
     *
     * @return mixed
     */
    public function getDeal()
    {
        $dealId = $this->request->get('id');

        
        if (!is_numeric($dealId)) {
            $res['success'] = 0;
            $res['message'] = 'Deal id is invalid';
            
            $this->api->setHeaders()->badRequest($res);
        }
        
        $offersModel = $this->load->model('Offers');
        $productModel = $this->load->model('Products');
        
        $deal = $offersModel->getDeal($dealId);
        
        $images = $productModel->getProductImages($deal->product_id);
        $count = count($images);

        for ($i = 0; $i < $count; $i++) {
            $images[$i] = assets('common/images/' . $images[$i]->name);
        }

        $deal->images = $images;

        $res['success'] = 1;
        $res['message'] = 'Getting deal successfully';
        $res['data']['deal'] = $deal;

        $this->api->setHeaders()->success($res);
    }
}
