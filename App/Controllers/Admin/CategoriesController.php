<?php

namespace App\Controllers\Admin;

use System\Controller;

class CategoriesController extends Controller
{
    /**
     * Display Categories List
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Categories');

        $data['categories'] = $this->load->model('Categories')->paginate();

        $data['pagination'] = $this->pagination->paginate();

        $data['url'] = $this->url->link('admin/categories?page=');

        $view = $this->view->render('admin/categories/categories', $data);

        return $this->adminLayout->render($view);
    }

    /**
     * search in categories
     *
     * @return mixed
     */
    public function search()
    {
        $this->html->setTitle('Categories');

        $searchKey = $this->request->get('search-key');

        $data['searchKey'] = $searchKey;

        $data['categories'] = $this->load->model('Categories')->search($searchKey);

        $data['pagination'] = $this->pagination->paginate();

        $data['url'] = $this->url->link('admin/categories/search?search-key=' . $searchKey . '&page=');

        $view = $this->view->render('admin/categories/categories', $data);

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

        $categoriesModel = $this->load->model('Categories');

        if (!$categoriesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Category not found';
            return json_encode($json);
        }

        $success = $categoriesModel->delete($id);

        if (!$success) {
            $json['success'] = false;
            $json['message'] = 'Failed to delete category';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['categoryId'] = $id;
        $json['message'] = 'Category deleted successfully';

        return json_encode($json);
    }

    /**
     * change category status
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

        $categoriesModel = $this->load->model('Categories');

        if (!$categoriesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Category not found';
            return json_encode($json);
        }

        $categoriesModel->updateStatus($id, $status);

        $json['success'] = true;
        $json['categoryId'] = $id;
        $json['status'] = $status;
        $json['message'] = 'Category status updated successfully';

        return json_encode($json);
    }

    /**
     * display edit category Form
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

        $categoriesModel = $this->load->model('Categories');

        if (!$categoriesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Category not found';
            return json_encode($json);
        }

        $data['category'] = $categoriesModel->get($id);

        $form = $this->view->render('admin/categories/edit-form', $data);

        $json['success'] = true;
        $json['data'] = htmlspecialchars($form);

        return json_encode($json);
    }

    /**
     * Submit for editing category
     *
     * @return string | json
     */
    public function editCategory()
    {
        $id = $this->request->post('id');
        $parentId = $this->request->post('parent-id');

        if (!is_numeric($id)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }
        if (!empty($parentId) && !is_numeric($parentId)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $categoriesModel = $this->load->model('Categories');

        if (!$categoriesModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Category not found';
            return json_encode($json);
        }

        if (!empty($parentId) && !$categoriesModel->exists($parentId)) {

            $json['success'] = false;
            $json['message'] = 'Parent category not found';
            return json_encode($json);
        }

        if (!empty($parentId) && $parentId == $id) {

            $json['success'] = false;
            $json['message'] = 'Invalid parent category';
            return json_encode($json);
        }

        if (!$this->isEditInputDataValid()) {
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $categoriesModel->update();

        $json['success'] = true;
        $json['categoryId'] = $id;
        $json['message'] = 'Category updated successfully';

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
        $this->validator->required('id')->int('id');
        $this->validator->required('name')->text('name');
        $this->validator->required('status')->isFormatIntBool('status');

        if (!empty($this->request->post('parent-id'))) {
            $this->validator->required('parent-id')->int('parent-id');
        }
        if (!empty($_FILES['image']['full_path'])) {
            $this->validator->requiredFile('image')->image('image');
        }

        return $this->validator->passes();
    }

    /**
     * display add category Form
     *
     * @return string
     */
    public function displayAddForm()
    {
        $form = $this->view->render('admin/categories/add-form');

        $json['success'] = true;
        $json['data'] = htmlspecialchars($form);

        return json_encode($json);
    }

    /**
     * Submit for adding category
     *
     * @return string | json
     */
    public function addCategory()
    {
        if (empty($_FILES['image']['full_path'])) {

            $json['success'] = false;
            $json['message'] = 'Image is required';
            return json_encode($json);
        }

        $parentId = $this->request->post('parent-id');

        if (!empty($parentId) && !is_numeric($parentId)) {

            $json['success'] = false;
            $json['message'] = 'Invalid parent id';
            return json_encode($json);
        }

        $categoriesModel = $this->load->model('Categories');

        if (!empty($parentId) && !$categoriesModel->exists($parentId)) {

            $json['success'] = false;
            $json['message'] = 'Parent category id not found';
            return json_encode($json);
        }

        if (!$this->isAddInputDataValid()) {
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $categoriesModel->create();

        $json['success'] = true;
        $json['message'] = 'Category created successfully';

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
        $this->validator->required('name')->text('name');
        $this->validator->required('status')->isFormatIntBool('status');

        if (!empty($this->request->post('parent-id'))) {
            $this->validator->required('parent-id')->int('parent-id');
        }
        if (!empty($_FILES['image']['full_path'])) {
            $this->validator->requiredFile('image')->image('image');
        }

        return $this->validator->passes();
    }
}
