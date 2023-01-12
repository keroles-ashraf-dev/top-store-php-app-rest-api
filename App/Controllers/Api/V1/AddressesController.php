<?php

namespace App\Controllers\Api\V1;

use System\Controller;

class AddressesController extends Controller
{
    /**
     * Addresss
     *
     * @return mixed
     */
    public function index()
    {
        $userId = '';
        $addressesModel = $this->load->model('Addresses');
        $addresses = $addressesModel->getAddresses($userId);

        $res['success'] = 1;
        $res['message'] = 'Getting addresses successfully';
        $res['data']['addresses'] = $addresses;

        $this->api->setHeaders()->success($res);
    }

    /**
     * get address
     *
     * @return mixed
     */
    public function getAddress()
    {
        $addressId = $this->request->get('id');

        if (!is_numeric($addressId)) {
            $res['success'] = 0;
            $res['message'] = 'Address id is invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        if($addressId == -1){
            $res['success'] = 1;
            $res['message'] = 'Getting address successfully';
            $res['data']['country'] = $this->security->getUserCountry();
    
            $this->api->setHeaders()->success($res);
        }

        $addressesModel = $this->load->model('Addresses');

        $address = $addressesModel->get($addressId);

        $res['success'] = 1;
        $res['message'] = 'Getting address successfully';
        $res['data']['address'] = $address;

        $this->api->setHeaders()->success($res);
    }
}
