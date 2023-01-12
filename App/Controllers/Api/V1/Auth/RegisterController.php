<?php

namespace App\Controllers\Api\V1\Auth;

use System\Controller;

class RegisterController extends Controller
{
    /**
     * register new user
     *
     * @return mixed
     */
    public function index()
    {
        if (!$this->isInputDataValid()) {
            $res['success'] = 0;
            $res['message'] = flatten($this->validator->getErrors());

            $this->api->setHeaders()->badRequest($res);
        }

        // get all user input data
        $userData = $this->request->fileGetContents('', '', true);

        $usersModel = $this->load->model('Users');

        $userId = $usersModel->create($userData);

        return $this->load->action('Api/V1/Auth/EmailVerifying', 'sendEmailOTP', [$userId, $userData['email']]);
    }

    /**
     * Validate the form
     *
     * @param int $id
     * @return bool
     */
    private function isInputDataValid()
    {
        $this->validator->required('first-name')->text('first-name');
        $this->validator->required('last-name')->text('last-name');
        $this->validator->required('email')->email('email');
        $this->validator->required('phone')->phone('phone');
        $this->validator->required('password')->minLen('password', 8)
            ->match('password', 'confirm-password');
        $this->validator->unique('email', ['users', 'email']);
        $this->validator->unique('phone', ['users', 'phone']);

        return $this->validator->passes();
    }
}
