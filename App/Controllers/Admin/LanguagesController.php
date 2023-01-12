<?php

namespace App\Controllers\Admin;

use System\Controller;

class LanguagesController extends Controller
{
    /**
     * Display Languages List
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Languages');

        $data['languages'] = $this->load->model('Languages')->all();

        $view = $this->view->render('admin/languages/languages', $data);

        return $this->adminLayout->render($view);
    }

    /**
     * Delete Record
     *
     * @param int $id
     * @return mixed
     */
    public function delete()
    {
        $id = $this->request->fileGetContents('id');

        if (!is_numeric($id)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $languagesModel = $this->load->model('Languages');

        if (!$languagesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Language not found';
            return json_encode($json);
        }

        $success = $languagesModel->delete($id);

        if (!$success) {
            $json['success'] = false;
            $json['message'] = 'Failed to delete language';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['languageId'] = $id;
        $json['message'] = 'Language deleted successfully';

        return json_encode($json);
    }

    /**
     * change language status
     *
     * @param int $id
     * @param int $status
     * @return mixed
     */
    public function changeStatus()
    {
        $id = $this->request->fileGetContents('id');
        $status = intval($this->request->fileGetContents('status'));

        if (!is_numeric($id) || !is_int($status)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $languagesModel = $this->load->model('Languages');

        if (!$languagesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Language not found';
            return json_encode($json);
        }

        $languagesModel->updateStatus($id, $status);

        $json['success'] = true;
        $json['languageId'] = $id;
        $json['status'] = $status;
        $json['message'] = 'Language status updated successfully';

        return json_encode($json);
    }

    /**
     * display edit language Form
     *
     * @return string
     */
    public function displayEditForm()
    {
        $id = $this->request->fileGetContents('id');

        if (!is_numeric($id)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $languagesModel = $this->load->model('Languages');

        if (!$languagesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Language not found';
            return json_encode($json);
        }

        $data['language'] = $languagesModel->get($id);
        $data['downloadUrl'] = url('download?file=' . $data['language']->file);

        $form = $this->view->render('admin/languages/edit-form', $data);

        $json['success'] = true;
        $json['data'] = htmlspecialchars($form);

        return json_encode($json);
    }

    /**
     * Submit for editing language
     *
     * @return string | json
     */
    public function edit()
    {
        $id = $this->request->post('id');

        if (!is_numeric($id)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $languagesModel = $this->load->model('Languages');

        if (!$languagesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Language not found';
            return json_encode($json);
        }

        if (!$this->isEditInputDataValid()) {
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $success = $languagesModel->update($id);

        if (!$success) {
            $json['success'] = false;
            $json['message'] = 'Failed to edit language';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['languageId'] = $id;
        $json['message'] = 'Language updated successfully';

        return json_encode($json);
    }

    /**
     * Validate the form
     *
     * @param int $id
     * @return bool
     */
    private function isEditInputDataValid()
    {

        $this->validator->required('name')->text('name')->maxLen('name', 64);
        $this->validator->required('code')->text('code')->maxLen('code', 6);
        $this->validator->required('status')->isFormatIntBool('status');

        if (!empty($_FILES['language-file']['full_path'])) {
            $this->validator->requiredFile("language-file")->json("language-file");
        }

        return $this->validator->passes();
    }

    /**
     * display add language Form
     *
     * @return string
     */
    public function displayAddForm()
    {
        $form = $this->view->render('admin/languages/add-form');

        $json['success'] = true;
        $json['data'] = htmlspecialchars($form);

        return json_encode($json);
    }

    /**
     * Submit for adding language
     *
     * @return string | json
     */
    public function add()
    {
        if (!$this->isAddInputDataValid()) {
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $languagesModel = $this->load->model('Languages');
        $success = $languagesModel->create();

        if (!$success) {
            $json['success'] = false;
            $json['message'] = 'Failed to add language file';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['message'] = 'Language added successfully';

        return json_encode($json);
    }

    /**
     * Validate the add form
     *
     * @param int $id
     * @return bool
     */
    private function isAddInputDataValid()
    {
        $this->validator->required('name')->text('name')->maxLen('name', 64);
        $this->validator->required('code')->text('code')->maxLen('code', 6);
        $this->validator->required('status')->isFormatIntBool('status');
        $this->validator->requiredFile("language-file")->json("language-file");

        return $this->validator->passes();
    }
}
