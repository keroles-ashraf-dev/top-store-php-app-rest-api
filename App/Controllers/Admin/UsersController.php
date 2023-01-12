<?php

namespace App\Controllers\Admin;

use System\Controller;

class UsersController extends Controller
{
    /**
     * Display Users  List
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Users ');

        $data['users'] = $this->load->model('Users')->paginate();

        $data['pagination'] = $this->pagination->paginate();

        $data['url'] = $this->url->link('admin/users' . '?page=');

        $view = $this->view->render('admin/users/users', $data);

        return $this->adminLayout->render($view);
    }

    /**
     * search in users
     *
     * @return mixed
     */
    public function search()
    {
        $this->html->setTitle('Users ');

        $searchKey = $this->request->post('search');

        $data['searchKey'] = $searchKey;

        $data['users'] = $this->load->model('Users')->search($searchKey);

        $data['pagination'] = $this->pagination->paginate();

        $data['url'] = $this->url->link('admin/users' . '?page=');

        $view = $this->view->render('admin/users/users', $data);

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

        $usersModel = $this->load->model('Users');

        if (!$usersModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'User not found';
            return json_encode($json);
        }

        $usersModel->delete($id);

        $json['success'] = true;
        $json['userId'] = $id;
        $json['message'] = 'User deleted successfully';

        return json_encode($json);
    }

    /**
     * change user status
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

        $usersModel = $this->load->model('Users');

        if (!$usersModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'User not found';
            return json_encode($json);
        }

        $usersModel->updateStatus($id, $status);

        $json['success'] = true;
        $json['userId'] = $id;
        $json['status'] = $status;
        $json['message'] = 'User status updated successfully';

        return json_encode($json);
    }

    /**
     * display edit user Form
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

        $usersModel = $this->load->model('Users');

        if (!$usersModel->exists($id)) {

            $json['success'] = false;
            $json['message'] = 'User not found';
            return json_encode($json);
        }

        $data['user'] = $usersModel->get($id);

        $form = $this->view->render('admin/users/edit-form', $data);

        $json['success'] = true;
        $json['data'] = htmlspecialchars($form);

        return json_encode($json);
    }

    /**
     * Submit for editing user
     *
     * @return string | json
     */
    public function save()
    {
        // get all user input data
        $userData = $this->request->fileGetContents('', '', true);

        if (!is_numeric($userData['id'])) {

            $json['success'] = false;
            $json['message'] = 'Invalid data';
            return json_encode($json);
        }

        $usersModel = $this->load->model('Users');

        if (!$usersModel->exists($userData['id'])) {

            $json['success'] = false;
            $json['message'] = 'User not found';
            return json_encode($json);
        }

        if (!$this->isInputDataValid($userData)) {
            // it means there are errors in form validation
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $usersModel->update($userData);

        $json['success'] = true;
        $json['userId'] = $userData['id'];
        $json['message'] = 'User updated successfully';

        return json_encode($json);
    }

    /**
     * Validate the form
     *
     * @param int $id
     * @return bool
     */
    private function isInputDataValid($userData)
    {
        $this->validator->required($userData['id'], null, true)->int($userData['id'], null, true);
        $this->validator->required($userData['first-name'], null, true)->text($userData['first-name'], null, true);
        $this->validator->required($userData['last-name'], null, true)->text($userData['last-name'], null, true);
        $this->validator->required($userData['email'], null, true)->email($userData['email'], null, true);
        $this->validator->required($userData['phone'], null, true)->phone($userData['phone'], null, true);
        $this->validator->required($userData['role'], null, true)->text($userData['role'], null, true);
        $this->validator->required($userData['status'], null, true)->isFormatIntBool($userData['status'], null, true);

        if (isset($userData['password']) && isset($userData['confirm-password'])) {
            $this->validator->required($userData['password'], null, true)->minLen($userData['password'], 8, null, true)
                ->match($userData['password'], $userData['confirm-password'], null, true);
        }

        $this->validator->unique($userData['email'], ['users', 'email', 'id', $userData['id']], null, true);
        $this->validator->unique($userData['phone'], ['users', 'phone', 'id', $userData['id']], null, true);

        return $this->validator->passes();
    }
}
