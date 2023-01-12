<?php

namespace App\Controllers\Store;

use System\Controller;

class ProfileDataController extends Controller
{
    /**
     * Display profile page
     *
     * @return mixed
     */
    public function index()
    {
        $loginModel = $this->load->model('Login');
        $data['user'] = $loginModel->user();

        $this->html->setTitle('Profile|Data');

        $view = $this->view->render('store/profile/data/data', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * Display profile name editing page
     *
     * @return mixed
     */
    public function displayChangeNamePage()
    {
        $user = $this->load->model('Login')->user();
        $data['firstName'] = $user->first_name;
        $data['lastName'] = $user->last_name;

        $this->html->setTitle('Profile|Data|Name');

        $view = $this->view->render('store/profile/data/name', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * Submit for editing user name
     *
     * @return string | json
     */
    public function saveNameChanges()
    {
        if (!$this->isNameInputDataValid()) {
            // it means there are errors in form validation
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $userId = $this->load->model('Login')->user()->id;
        $usersModel = $this->load->model('Users');

        $userData = [
            'first-name' => $this->request->post('first-name'),
            'last-name' => $this->request->post('last-name'),
            'id' => $userId
        ];

        $success = $usersModel->update($userData);

        if (!$success) {
            // it means there are errors during update database
            $json['success'] = false;
            $json['message'] = 'Name updated failed';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['message'] = 'Name updated successfully';

        return json_encode($json);
    }

    /**
     * Validate the name changes form
     *
     * @param int $id
     * @return bool
     */
    private function isNameInputDataValid()
    {
        $this->validator->required('first-name')->text('first-name');
        $this->validator->required('last-name')->text('last-name');

        return $this->validator->passes();
    }

    /**
     * Display profile email editing page
     *
     * @return mixed
     */
    public function displayChangeEmailPage()
    {
        $user = $this->load->model('Login')->user();
        $data['email'] = $user->email;

        $this->html->setTitle('Profile|Data|Email');

        $view = $this->view->render('store/profile/data/email', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * Submit for editing user email
     *
     * @return string | json
     */
    public function sendEmailOTP()
    {
        if (!$this->security->isValidToSendEmail()) {
            // it means he asks for emails many times
            $json['success'] = false;
            $json['message'] = "You have to wait for " . $this->security->getRemainingTimeToSendEmail() . " minutes then try again";
            return json_encode($json);
        }

        $id = $this->load->model('Login')->user()->id;

        if (!$this->isEmailInputDataValid($id)) {
            // it means there are errors in form validation
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        // generate otp
        $otp = $this->security->setNewOtpToStorageAndReturnIt('email-verifying-otp', 'session');
        $message = "Use this code to verify your email\n $otp";
        $email = $this->request->post('email');

        // send otp to user email
        $sent = $this->messaging->email($email, 'verify your email', $message);

        if (!$sent) {
            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again later';
            return json_encode($json);
        }

        $this->session->set('temp-email', $email);
        $this->security->setSentEmailsInfoToCookie();

        $json['redirectTo'] = $this->url->link('/profile/data/email/verify-otp');
        return json_encode($json);
    }

    /**
     * Display email verify otp Page
     *
     * @return mixed
     */
    public function displayVerifyEmailPage()
    {
        $email = $this->session->get('temp-email');

        if (empty($email)) $this->url->redirectTo('/404');

        $data['email'] = $email;

        $this->html->setTitle('Profile|Data|EmailOTP');

        $view = $this->view->render('store/profile/data/email-otp', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * resend email otp
     *
     * @return json
     */
    public function resendEmailOTP()
    {

        if (!$this->security->isValidToSendEmail()) {
            // it means he asks for emails many times
            $json['success'] = false;
            $json['message'] = "You have to wait for " . $this->security->getRemainingTimeToSendEmail() . " minutes then try again";
            return json_encode($json);
        }

        // generate otp
        $otp = $this->security->setNewOtpToStorageAndReturnIt('email-verifying-otp', 'session');
        $message = "Use this code to verify your email\n $otp";
        $email = $this->session->get('temp-email');

        // send otp to user email
        $sent = $this->messaging->email($email, 'verify your email', $message);

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
     * Submit for email otp verification
     *
     * @return json
     */
    public function verifyEmailOtp()
    {
        if (!$this->isEmailOtpValid()) {
            // it means form token not valid
            $json['success'] = false;
            $json['message'] = 'Wrong OTP. you can ask to send new one';
            return json_encode($json);
        }

        $email = $this->session->pull('temp-email');

        $userId = $this->load->model('Login')->user()->id;

        if (!$this->isEmailInputDataValid($userId, $email)) {
            // it means there are errors in data validation (someone change user data which stored in session)
            $json['success'] = false;
            $json['message'] = 'Something wrong happens. try again';
            $json['redirect-to'] = $this->url->link('/logout');
            return json_encode($json);
        }

        $userData = [
            'email' => $email,
            'id' => $userId
        ];

        $success = $this->load->model('Users')->update($userData);

        if (!$success) {
            // it means there are errors during update database
            $json['success'] = false;
            $json['message'] = 'Email updated failed';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['message'] = 'Account email has been updated successfully.';
        $json['redirectTo'] = $this->url->link('/profile/data');

        return json_encode($json);
    }

    /**
     * check email otp if valid
     * 
     * @return bool
     */
    private function isEmailOtpValid()
    {
        $otp = $this->session->get('email-verifying-otp');
        // get user input otp
        $userOTP = $this->request->post('email-otp');

        return $this->security->isIdentical($otp, $userOTP);
    }

    /**
     * Validate the name changes form
     *
     * @param int $id
     * @return bool
     */
    private function isEmailInputDataValid($id, $email = null)
    {
        if ($email == null) {
            $this->validator->required('email')->email('email')->maxLen('email', 96)
                ->unique('email', ['users', 'email', 'id', $id]);
        } else {
            $this->validator->required($email, '', true)->email($email, '', true)
                ->maxLen($email, 96, '', true)
                ->unique($email, ['users', 'email', 'id', $id], null, true);;
        }

        return $this->validator->passes();
    }

    /**
     * Display profile phone editing page
     *
     * @return mixed
     */
    public function displayChangePhonePage()
    {
        $data['phone'] = $this->load->model('Login')->user()->phone;

        $this->html->setTitle('Profile|Data|Phone');

        $view = $this->view->render('store/profile/data/phone', $data);

        return $this->storeLayout->render($view);
    }

    /**
     * Submit for editing user phone
     *
     * @return string | json
     */
    public function savePhoneChanges()
    {
        if (!$this->isPhoneInputDataValid()) {
            // it means there are errors in form validation
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $userId = $this->load->model('Login')->user()->id;
        $usersModel = $this->load->model('Users');

        $userData = [
            'phone' => $this->request->post('phone'),
            'id' => $userId
        ];

        $success = $usersModel->update($userData);

        if (!$success) {
            // it means there are errors during update database
            $json['success'] = false;
            $json['message'] = 'Phone updated failed';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['message'] = 'Phone updated successfully';

        return json_encode($json);
    }

    /**
     * Validate the phone changes form
     *
     * @param int $id
     * @return bool
     */
    private function isPhoneInputDataValid()
    {
        $this->validator->required('phone')->phone('phone')->maxLen('phone', 32);

        return $this->validator->passes();
    }

    /**
     * Display profile Password editing page
     *
     * @return mixed
     */
    public function displayChangePasswordPage()
    {
        $this->html->setTitle('Profile|Data|Password');

        $view = $this->view->render('store/profile/data/password');

        return $this->storeLayout->render($view);
    }

    /**
     * Submit for editing user Password
     *
     * @return string | json
     */
    public function savePasswordChanges()
    {
        if (!$this->isPasswordInputDataValid()) {
            // it means there are errors in form validation
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        if (!$this->isCurrentPasswordValid()) {
            $json['success'] = false;
            $json['message'] = 'Current password is wrong';
            return json_encode($json);
        }

        $userId = $this->load->model('Login')->user()->id;
        $usersModel = $this->load->model('Users');

        $userData = [
            'password' => $this->request->post('new-password'),
            'id' => $userId
        ];

        $success = $usersModel->update($userData);

        if (!$success) {
            // it means there are errors during update database
            $json['success'] = false;
            $json['message'] = 'Password updated failed';
            return json_encode($json);
        }

        $json['success'] = true;
        $json['message'] = 'Password updated successfully';
        $json['redirectTo'] = $this->url->link('/profile/data');
        $json['redirectToDelay'] = 2000;

        return json_encode($json);
    }

    /**
     * Validate the phone changes form
     *
     * @param int $id
     * @return bool
     */
    private function isPasswordInputDataValid()
    {
        $this->validator->required('new-password')->minLen('new-password', 8)
            ->match('new-password', 'confirm-new-password');

        return $this->validator->passes();
    }

    /**
     * Validate the current password
     *
     * @param int $id
     * @return bool
     */
    private function isCurrentPasswordValid()
    {
        $currentPasswordHashed = $this->load->model('Login')->user()->password;
        $userPassword = $this->request->post('current-password');

        if (!password_verify($userPassword, $currentPasswordHashed)) return false;

        return true;
    }
}
