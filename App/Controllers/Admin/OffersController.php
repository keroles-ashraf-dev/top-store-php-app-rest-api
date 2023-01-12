<?php

namespace App\Controllers\Admin;

use System\Controller;

class OffersController extends Controller
{
    /**
     * Display Offers List
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Offers');

        $offersModel = $this->load->model('Offers');

        $data['sliders'] = $offersModel->all($offersModel->sliderTable());;
        $data['deals'] = $offersModel->getDeals();;

        $view = $this->view->render('admin/offers/offers', $data);

        return $this->adminLayout->render($view);
    }

    /**
     * Delete Slider Record
     *
     * @param int $id
     * @return mixed
     */
    public function deleteSlider()
    {
        $id = $this->request->fileGetContents('id');

        if (!is_numeric($id)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $offersModel = $this->load->model('Offers');

        if (!$offersModel->exists($id, 'id', $offersModel->sliderTable())) {

            $json['success'] = false;
            $json['message'] = 'Slider not found';
            return json_encode($json);
        }

        $offersModel->deleteSlider($id);

        $json['success'] = true;
        $json['sliderId'] = $id;
        $json['message'] = 'Slider deleted successfully';

        return json_encode($json);
    }

    /**
     * change slider status
     *
     * @param int $id
     * @param int $status
     * @return mixed
     */
    public function changeSliderStatus()
    {
        $id = $this->request->fileGetContents('id');
        $status = intval($this->request->fileGetContents('status'));

        if (!is_numeric($id) || !is_int($status)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $offersModel = $this->load->model('Offers');

        if (!$offersModel->exists($id, 'id', $offersModel->sliderTable())) {

            $json['success'] = false;
            $json['message'] = 'Slider item not found';
            return json_encode($json);
        }

        $offersModel->updateStatus($id, $status, $offersModel->sliderTable());

        $json['success'] = true;
        $json['sliderId'] = $id;
        $json['status'] = $status;
        $json['message'] = 'slider item status updated successfully';

        return json_encode($json);
    }

    /**
     * display add product Form
     *
     * @return string
     */
    public function displayAddSliderForm()
    {
        $form = $this->view->render('admin/offers/add-slider-form');

        $json['success'] = true;
        $json['data'] = htmlspecialchars($form);

        return json_encode($json);
    }

    /**
     * Submit for adding slider
     *
     * @return string | json
     */
    public function addSlider()
    {
        if (empty($_FILES['slider-image']['tmp_name'])) {

            $json['success'] = false;
            $json['message'] = 'Image is required';
            return json_encode($json);
        }

        if (!$this->isAddSliderInputDataValid()) {
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $offersModel = $this->load->model('Offers');
        $offersModel->createSlider();

        $json['success'] = true;
        $json['message'] = 'Slider created successfully';

        return json_encode($json);
    }

    /**
     * Validate the add form
     *
     * @param int $id
     * @return bool
     */
    private function isAddSliderInputDataValid()
    {
        $this->validator->requiredFile("slider-image")->image("slider-image");
        return $this->validator->passes();
    }

    /**
     * Delete Deal Record
     *
     * @param int $id
     * @return mixed
     */
    public function deleteDeal()
    {
        $id = $this->request->fileGetContents('id');

        if (!is_numeric($id)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $offersModel = $this->load->model('Offers');

        if (!$offersModel->exists($id, 'id', $offersModel->dealsTable())) {

            $json['success'] = false;
            $json['message'] = 'Deal not found';
            return json_encode($json);
        }

        $offersModel->delete($id, $offersModel->dealsTable());

        $json['success'] = true;
        $json['dealId'] = $id;
        $json['message'] = 'Deal deleted successfully';

        return json_encode($json);
    }

    /**
     * change Deal status
     *
     * @param int $id
     * @param int $status
     * @return mixed
     */
    public function changeDealStatus()
    {
        $id = $this->request->fileGetContents('id');
        $status = intval($this->request->fileGetContents('status'));

        if (!is_numeric($id) || !is_int($status)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $offersModel = $this->load->model('Offers');

        if (!$offersModel->exists($id, 'id', $offersModel->dealsTable())) {

            $json['success'] = false;
            $json['message'] = 'Deal item not found';
            return json_encode($json);
        }

        $offersModel->updateStatus($id, $status, $offersModel->dealsTable());

        $json['success'] = true;
        $json['dealId'] = $id;
        $json['status'] = $status;
        $json['message'] = 'Deal item status updated successfully';

        return json_encode($json);
    }

    /**
     * display add deal Form
     *
     * @return string
     */
    public function displayAddDealForm()
    {
        $form = $this->view->render('admin/offers/add-deal-form');

        $json['success'] = true;
        $json['data'] = htmlspecialchars($form);

        return json_encode($json);
    }

    /**
     * Submit for editing user
     *
     * @return string | json
     */
    public function addDeal()
    {
        // get all deal input data
        $dealData = $this->request->fileGetContents('', '', true);

        if (!is_numeric($dealData['product-id'])) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $productsModel = $this->load->model('Products');

        if (!$productsModel->exists($dealData['product-id'])) {

            $json['success'] = false;
            $json['message'] = 'Product not found';
            return json_encode($json);
        }

        if (!$this->isAddDealInputDataValid($dealData)) {
            // it means there are errors in form validation
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $this->load->model('Offers')->createDeal($dealData);

        $json['success'] = true;
        $json['message'] = 'Deal added successfully';

        return json_encode($json);
    }

    /**
     * Validate the form
     *
     * @param int $dealData
     * @return bool
     */
    private function isAddDealInputDataValid($dealData)
    {
        $this->validator->required($dealData['price'], null, true)->float($dealData['price'], null, true);
        $this->validator->required($dealData['status'], null, true)->isFormatIntBool($dealData['status'], null, true);

        return $this->validator->passes();
    }
}
