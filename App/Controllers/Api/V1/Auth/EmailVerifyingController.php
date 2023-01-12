<?php

namespace App\Controllers\Api\V1\Auth;

use System\Controller;

class EmailVerifyingController extends Controller
{
    /**
     * verify Email OTP
     *
     * @return mixed
     */
    public function index()
    {
        $email = $this->request->get("email");
        $otp = $this->request->get("otp");

        if (!$this->isInputDataValid($email) || !is_numeric($otp)) {
            return 'Email or OTP is invalid';
        }

        if (!$this->isOtpValid($email, $otp)) {
            return 'Email or OTP is invalid';
        }

        $otpModel = $this->load->model('OTP');
        $usersModel = $this->load->model('Users');

        $userData['id'] = $otpModel->getUserIdByEmail($email);
        $userData['email_verified'] = 1;

        $usersModel->update($userData);

        return 'Email verified successfully';
    }

    /**
     * send Email OTP
     *
     * @return mixed
     */
    public function sendEmailOTP($userId, $userEmail)
    {
        if (!$this->isInputDataValid()) {
            $res['success'] = 0;
            $res['message'] = 'Email is invalid';
            $this->api->setHeaders()->badRequest($res);
        }

        // generate otp
        $otp = $this->security->generateOTP();
        $link = url('/api/v1/verify-email?email=' . $userEmail . '&otp=' . $otp);
        $message = "Use this link to verify your email\n $link";

        // send otp to user email
        $sent = $this->messaging->email($userEmail, 'verify your email', $message);

        if (!$sent) {
            $res['success'] = 0;
            $res['message'] = 'Server internal error, try again later';
            $this->api->setHeaders()->internalError($res);
        }

        $otpModel = $this->load->model('OTP');

        $otpModel->setEmailVerifyingOTP($userId, $userEmail, $otp);

        $res['success'] = 0;
        $res['message'] = 'Verification OTP sent to your email';
        $res['data']['email'] = $userEmail;
        $this->api->setHeaders()->unauthorized($res);
    }

    /**
     * resend otp
     *
     * @return json
     */
    public function resendOTP()
    {
        $email = $this->request->fileGetContents("email");

        if (!$this->isInputDataValid()) {
            $res['success'] = 0;
            $res['message'] = 'Email is invalid';
            $this->api->setHeaders()->badRequest($res);
        }

        $otpModel = $this->load->model('OTP');

        $userId = $otpModel->getUserIdByEmail($email);

        // generate otp
        $otp = $this->security->generateOTP();
        $link = url('/verify-email?email=' . $email . '&otp=' . $otp);
        $message = "Use this link to verify your email\\n $link";
        // send otp to user email
        $sent = $this->messaging->email($email, 'verify your email', $message);

        if (!$sent) {
            $res['success'] = 0;
            $res['message'] = 'Server internal error, try again later';
            $this->api->setHeaders()->internalError($res);
        }

        $otpModel->setEmailVerifyingOTP($userId, $email, $otp);

        $res['success'] = 0;
        $res['message'] = 'Verification OTP sent to your email';
        $res['data']['email'] = $email;
        $this->api->setHeaders()->unauthorized($res);
    }

    /**
     * check otp if valid
     * 
     * @return bool
     */
    private function isOtpValid($email, $userOTP)
    {
        $otpModel = $this->load->model('OTP');

        $otp = $otpModel->getEmailOTP($email);

        return $this->security->isIdentical($otp, $userOTP);
    }

    /**
     * Validate Login Form
     *
     * @return bool
     */
    private function isInputDataValid($email = null)
    {
        if ($email == null) {
            $this->validator->required('email')->email('email');
        } else {
            $this->validator->required($email, null, true)->email($email, null, true);
        }

        if (!$this->validator->passes()) {
            return false;
        }

        return true;
    }
}
