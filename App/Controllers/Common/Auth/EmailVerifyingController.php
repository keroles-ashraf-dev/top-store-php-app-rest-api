<?php

namespace App\Controllers\Common\Auth;

use System\Controller;

class EmailVerifyingController extends Controller
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

        $userEmail = $this->session->get('temp-user')['email'];

        if (!$userEmail) {
            return $this->url->redirectTo('/');
        }

        $data['email'] = $userEmail;

        $token = $this->security->setNewTokenToStorageAndReturnIt('form-token', 'session');

        $data['token'] = $token;

        $this->storeLayout->title('Email Verifying');

        // disable navbar
        $this->storeLayout->disable('navbar');

        $view = $this->view->render('common/auth/email-verifying', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * Submit for creating new user
     *
     * @return json
     */
    public function submit()
    {
        if (!$this->security->isUserInputTokenValid('form-token')) {
            // it means form token not valid
            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again';
            return json_encode($json);
        }

        if (!$this->isOtpValid()) {
            // it means form token not valid
            $json['success'] = false;
            $json['message'] = 'Wrong OTP. you can ask to send new one';
            return json_encode($json);
        }


        if (!$this->isInputDataValid()) {
            // it means there are errors in data validation (someone change user data which stored in session)
            $this->session->destroy();

            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again';
            $json['redirect-to'] = $this->url->link('/register');
            return json_encode($json);
        }

        $tempUser = $this->session->get('temp-user');
        $this->load->model('Users')->create($tempUser);

        $this->session->destroy();

        $json['success'] = true;
        $json['message'] = 'Account has been created successfully. you will be redirected to login';
        $json['redirect-to'] = $this->url->link('/login');

        return json_encode($json);
    }

    /**
     * resend otp
     *
     * @return json
     */
    public function resendOTP()
    {

        if (!$this->security->isValidToSendEmail()) {
            // it means he asks for emails many times
            $json['success'] = false;
            $json['message'] = "You have to wait for " . $this->security->getRemainingTimeToSendEmail() . " minutes then try again";
            return json_encode($json);
        }

        $userEmail = $this->session->get('temp-user')['email'];

        // generate otp
        $otp = $this->security->setNewOtpToStorageAndReturnIt('email-verifying-otp', 'session');
        $message = "Use this code to verify your email\n $otp";

        // send otp to user email
        $sent = $this->messaging->email($userEmail, 'verify your email', $message);

        if (!$sent) {
            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $this->security->setSentEmailsInfoToCookie();

        $json['success'] = true;
        $json['message'] = 'New email verifying OTP has been sent';
        return json_encode($json);
    }

    /**
     * check otp if valid
     * 
     * @return bool
     */
    private function isOtpValid()
    {
        $otp = $this->session->get('email-verifying-otp');
        // get user input otp
        $userOTP = $this->request->fileGetContents('email-verifying-otp');

        return $this->security->isIdentical($otp, $userOTP);
    }

    /**
     * Validate the user data which stored in session
     *
     * @param int $id
     * @return bool
     */
    private function isInputDataValid()
    {
        $user = $this->session->get('temp-user');

        $this->validator->required($user['first-name'], '', true)->text($user['first-name'], '', true);
        $this->validator->required($user['last-name'], '', true)->text($user['last-name'], '', true);
        $this->validator->required($user['email'], '', true)->email($user['email'], '', true);
        $this->validator->required($user['phone'], '', true)->phone($user['phone'], '', true);
        $this->validator->required($user['password'], '', true)->minLen($user['password'], 8, '', true)
            ->match($user['password'], $user['confirm-password'], '', true);
        $this->validator->unique($user['email'], ['users', 'email'], '', true);
        $this->validator->unique($user['phone'], ['users', 'phone'], '', true);

        return $this->validator->passes();
    }
}
