<?php

namespace App\Controllers\Common\Auth;

use System\Controller;

class RegisterController extends Controller
{
    /**
     * Display Registration Page
     *
     * @return mixed
     */
    public function index()
    {

        $loginModel = $this->load->model('Login');

        if ($loginModel->isLogged()) {
            return $this->url->redirectTo('/');
        }

        $this->storeLayout->title('Create New Account');

        // disable navbar
        $this->storeLayout->disable('navbar');

        $userData = $this->session->get('temp-user');

        if ($userData) {
            $data['firstName'] = $userData['first-name'];
            $data['lastName'] = $userData['last-name'];
            $data['email'] = $userData['email'];
        }
        
        $token = $this->security->setNewTokenToStorageAndReturnIt('form-token', 'session');
        
        $data['token'] = $token;

        $view = $this->view->render('common/auth/register', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * Submit to check user data and send OTP
     *
     * @return json
     */
    public function submit()
    {
        //console_log('session2', $_SESSION);

        if (!$this->security->isUserInputTokenValid('form-token')) {
            // it means form token not valid
            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        if (!$this->security->isValidToSendEmail()) {
            // it means he asks for emails many times
            $json['success'] = false;
            $json['message'] = "You have to wait for " . $this->security->getRemainingTimeToSendEmail() . " minutes then try again";
            return json_encode($json);
        }

        if (!$this->isInputDataValid()) {
            // it means there are errors in form validation
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        // get all user input data
        $userData = $this->request->fileGetContents('', '', true);

        // generate otp
        $otp = $this->security->setNewOtpToStorageAndReturnIt('email-verifying-otp', 'session');
        $message = "Use this code to verify your email\n $otp";

        // send otp to user email
        $sent = $this->messaging->email($userData['email'], 'verify your email', $message);

        if (!$sent) {
            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $this->session->set('temp-user', $userData);
        $this->security->setSentEmailsInfoToCookie();

        $json['redirect-to'] = $this->url->link('/email-verifying');
        return json_encode($json);
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
