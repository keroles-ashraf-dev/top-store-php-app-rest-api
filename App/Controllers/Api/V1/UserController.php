<?php

namespace App\Controllers\Api\V1;

use System\Controller;

class UserController extends Controller
{
    /**
     * return user data
     *
     * @return mixed
     */
    public function index()
    {
        $headers = $this->api->getHeaders();

        $token = $headers['authorization'] ?? '';

        // token is empty so send unauthorized request
        if (empty($token)) {
            $res['success'] = 0;
            $res['message'] = 'Auth token is invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $loginModel = $this->load->model('Login');

        $isValid =  $loginModel->isTokenValid($token);

        // token is not valid so send unauthorized request
        if (!$isValid) {
            $res['success'] = 0;
            $res['message'] = 'Auth token is invalid';

            $this->api->setHeaders()->unauthorized($res);
        }

        $usersModel = $this->load->model('Users');

        $user = $usersModel->getUserByToken($token);

        if (empty($user)) {
            $res['success'] = 0;
            $res['message'] = 'Server internal error, try again later';

            $this->api->setHeaders()->internalError($res);
        }

        unset($user->password, $user->role, $user->ip);

        $user->token = $token;

        $res['success'] = 1;
        $res['message'] = 'Getting user data successfully';
        $res['data']['user'] = $user;

        $this->api->setHeaders()->success($res);
    }

    /**
     * Validate Login Form
     *
     * @return bool
     */
    private function isInputDataValid()
    {
        $this->validator->required('email')->email('email');
        $this->validator->required('password')->minLen('password', 8);

        if (!$this->validator->passes()) {
            return false;
        }

        return true;
    }
}
