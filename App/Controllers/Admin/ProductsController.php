<?php

namespace App\Controllers\Admin;

use System\Controller;

class ProductsController extends Controller
{
    /**
     * Display Products List
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Products');

        $data['products'] = $this->load->model('Products')->paginate();

        $data['pagination'] = $this->pagination->paginate();

        $data['url'] = $this->url->link('admin/products?page=');

        $view = $this->view->render('admin/products/products', $data);

        return $this->adminLayout->render($view);
    }

    /**
     * search in products
     *
     * @return mixed
     */
    public function search()
    {
        $this->html->setTitle('Products');

        $searchKey = $this->request->get('search-key');

        $data['searchKey'] = $searchKey;

        $data['products'] = $this->load->model('Products')->search($searchKey);

        $data['pagination'] = $this->pagination->paginate();

        $data['url'] = $this->url->link('admin/products/search?search-key=' . $searchKey . '&page=');

        $view = $this->view->render('admin/products/products', $data);

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

        $productsModel = $this->load->model('Products');

        if (!$productsModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Product not found';
            return json_encode($json);
        }

        $productsModel->delete($id);

        $json['success'] = true;
        $json['productId'] = $id;
        $json['message'] = 'Product deleted successfully';

        return json_encode($json);
    }

    /**
     * change product status
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

        $productsModel = $this->load->model('Products');

        if (!$productsModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Product not found';
            return json_encode($json);
        }

        $productsModel->updateStatus($id, $status);

        $json['success'] = true;
        $json['productId'] = $id;
        $json['status'] = $status;
        $json['message'] = 'Product status updated successfully';

        return json_encode($json);
    }

    /**
     * display edit product Form
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

        $productsModel = $this->load->model('Products');

        if (!$productsModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Product not found';
            return json_encode($json);
        }

        $categoriesModel = $this->load->model('Categories');

        $data['product'] = $productsModel->get($id);
        $data['images'] = $productsModel->getProductImages($id);
        $data['categories'] = $categoriesModel->getCategoriesIdAndName();

        $form = $this->view->render('admin/products/edit-form', $data);

        $json['success'] = true;
        $json['data'] = htmlspecialchars($form);

        return json_encode($json);
    }

    /**
     * Submit for editing product
     *
     * @return string | json
     */
    public function editProduct()
    {
        $id = $this->request->post('id');

        if (!is_numeric($id)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $categoryId = $this->request->post('category-id');

        if (!is_numeric($categoryId)) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $productsModel = $this->load->model('Products');

        if (!$productsModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'Product not found';
            return json_encode($json);
        }

        $categoriesModel = $this->load->model('Categories');

        if (!$categoriesModel->exists($categoryId)) {

            $json['success'] = false;
            $json['message'] = 'Category not found';
            return json_encode($json);
        }

        if (!$this->isEditInputDataValid()) {
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $productsModel->update($id);

        $json['success'] = true;
        $json['productId'] = $id;
        $json['message'] = 'Product updated successfully';

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

        $this->validator->required('name')->text('name')->maxLen('name', 55);
        $this->validator->required('description')->text('description')->maxLen('description', 500);
        $this->validator->required('price')->float('price')->maxLen('price', 11);
        $this->validator->required('available-count')->int('available-count')->maxLen('available-count', 6);
        $this->validator->required('status')->isFormatIntBool('status');

        $countFiles = count($_FILES);

        for ($i = 0; $i < $countFiles; $i++) {
            $this->validator->requiredFile("product-image-$i")->image("product-image-$i");
        }

        return $this->validator->passes();
    }

    /**
     * display add product Form
     *
     * @return string
     */
    public function displayAddForm()
    {
        $categoriesModel = $this->load->model('Categories');

        $data['categories'] = $categoriesModel->getCategoriesIdAndName();

        $form = $this->view->render('admin/products/add-form', $data);

        $json['success'] = true;
        $json['data'] = htmlspecialchars($form);

        return json_encode($json);
    }

    /**
     * Submit for adding product
     *
     * @return string | json
     */
    public function addProduct()
    {
        if (empty($_FILES['product-image-0']['full_path'])) {

            $json['success'] = false;
            $json['message'] = 'One image at least required';
            return json_encode($json);
        }

        $categoryId = $this->request->post('category-id');

        if (empty($categoryId) || !is_numeric($categoryId)) {

            $json['success'] = false;
            $json['message'] = 'Invalid parent category';
            return json_encode($json);
        }

        $categoriesModel = $this->load->model('Categories');

        if (!$categoriesModel->exists($categoryId)) {

            $json['success'] = false;
            $json['message'] = 'Parent category not found';
            return json_encode($json);
        }

        if (!$this->isAddInputDataValid()) {
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $productsModel = $this->load->model('Products');
        $productsModel->create();

        $json['success'] = true;
        $json['message'] = 'Product created successfully';

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
        $this->validator->required('name')->text('name')->maxLen('name', 55);
        $this->validator->required('description')->text('description')->maxLen('description', 500);
        $this->validator->required('price')->float('price')->maxLen('price', 11);
        $this->validator->required('available-count')->int('available-count')->maxLen('available-count', 6);
        $this->validator->required('status')->isFormatIntBool('status');

        $countFiles = count($_FILES);
        //pred($_FILES);

        for ($i = 0; $i < $countFiles; $i++) {

            $this->validator->requiredFile("product-image-$i")->image("product-image-$i");
        }

        return $this->validator->passes();
    }
}
