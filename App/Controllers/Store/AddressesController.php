<?php

namespace App\Controllers\Store;

use System\Controller;

class AddressesController extends Controller
{
    /**
     * Display profile page
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Profile|Addresses');

        $user = $this->load->model('Login')->user();

        $data['addresses'] = $this->load->model('Addresses')->getAddresses($user->id);
        $data['default'] = $user->default_address_id;

        $view = $this->view->render('store/profile/addresses/addresses', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * set default address
     *
     * @return mixed
     */
    public function setDefault()
    {
        $addressId = $this->request->post('id');

        $addressesModel = $this->load->model('Addresses');

        if (!$addressesModel->exists($addressId)) {

            $json['success'] = false;
            $json['message'] = 'Address not found';
            return json_encode($json);
        }

        $user = $this->load->model('Login')->user();

        $userData['id'] = $user->id;
        $userData['default-address-id'] = $addressId;

        $this->load->model('Users')->update($userData);

        $json['success'] = true;
        $json['message'] = 'Default address set successfully';
        $json['redirectTo'] = $this->url->link('/profile/addresses');

        return json_encode($json);
    }

    /**
     * Display add address page
     *
     * @return mixed
     */
    public function displayAddPage()
    {
        $this->html->setTitle('Profile|Addresses|Add');

        $view = $this->view->render('store/profile/addresses/add');

        return $this->storeLayout->render($view);
    }

    /**
     * Submit for add address
     *
     * @return string | json
     */
    public function addAddress()
    {
        if (!$this->isAddressInputDataValid()) {
            // it means there are errors in data validation
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $user = $this->load->model('Login')->user();

        $addressId = $this->load->model('Addresses')->create($user->id);

        if (empty($user->default_address_id)) {

            $userData['id'] = $user->id;
            $userData['default-address-id'] = $addressId;

            $this->load->model('Users')->update($userData);
        }

        $json['success'] = true;
        $json['message'] = 'Address added successfully';
        $json['redirectTo'] = $this->url->link('/profile/addresses');

        return json_encode($json);
    }

    /**
     * Display edit address page
     *
     * @return mixed
     */
    public function displayEditPage()
    {
        $this->html->setTitle('Profile|Addresses|Edit');

        $addressId = $this->request->get('id');

        $data['address'] = $this->load->model('Addresses')->get($addressId);

        $view = $this->view->render('store/profile/addresses/edit', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * Submit for edit address
     *
     * @return string | json
     */
    public function editAddress()
    {
        $id = $this->request->post('id');

        if (!is_numeric($id)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $addressesModel = $this->load->model('Addresses');

        if (!$addressesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Address not found';
            return json_encode($json);
        }

        if (!$this->isAddressInputDataValid()) {
            // it means there are errors in data validation
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $success = $addressesModel->update($id);

        if (!$success) {
            // it means there are errors during update database
            $json['success'] = false;
            $json['message'] = 'Address updated failed';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['message'] = 'Address updated successfully';

        return json_encode($json);
    }

    /**
     * Delete address
     *
     * @return mixed
     */
    public function removeAddress()
    {
        $id = $this->request->post('id');

        if (!is_numeric($id)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $addressesModel = $this->load->model('Addresses');

        if (!$addressesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Address not found';
            return json_encode($json);
        }

        $success = $addressesModel->delete($id);

        if (!$success) {
            // it means there are errors during update database
            $json['success'] = false;
            $json['message'] = 'Address removed failed';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['message'] = 'Address removed successfully';
        $json['redirectTo'] = $this->url->link('/profile/addresses');

        return json_encode($json);
    }

    /**
     * Validate the address data
     *
     * @return bool
     */
    private function isAddressInputDataValid()
    {
        $this->validator->required('country')->text('country')->maxLen('country', 60);
        $this->validator->required('state')->text('state')->maxLen('state', 60);
        $this->validator->required('city')->text('city')->maxLen('city', 60);
        $this->validator->required('area')->text('area')->maxLen('area', 60);
        $this->validator->required('street')->text('street')->maxLen('street', 60);
        $this->validator->required('building')->text('building')->maxLen('building', 60);
        $this->validator->required('nearest-landmark')->text('nearest-landmark')->maxLen('nearest-landmark', 160);
        $this->validator->required('postcode')->text('postcode')->maxLen('postcode', 24);
        $this->validator->required('floor')->int('floor')->maxLen('floor', 3);

        return $this->validator->passes();
    }
}
