<?php

namespace App\Controllers\Api\V1\Auth;

use System\Controller;

class LoginController extends Controller
{
    /**
     * Deals
     *
     * @return mixed
     */
    public function index()
    {
        // email or password is invalid so send unauthorized request
        if (!$this->isInputDataValid()) {
            $res['success'] = 0;
            $res['message'] = 'Login data is invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $loginModel = $this->load->model('Login');

        $isValidLogin = $loginModel->isValidLogin();

        if (!$isValidLogin) {
            $res['success'] = 0;
            $res['message'] = 'Login data is invalid';

            $this->api->setHeaders()->unauthorized($res);
        }

        $user = $loginModel->user();

        if ($user->status == 0) {
            $res['success'] = 0;
            $res['message'] = 'Your account is disabled, contact us';

            $this->api->setHeaders()->unauthorized($res);
        }

        if ($user->email_verified == 0) {
            return $this->load->action('Api/V1/Auth/EmailVerifying', 'sendEmailOTP', [$user->id, $user->email]);
        }

        $token = $this->security->generateToken();
        $loginModel->setAuthToken($token);

        $user = $loginModel->user();

        $res['success'] = 1;
        $res['message'] = 'Successfully logged in';
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
