<?php

namespace App\Models;

use System\Model;

class OTPModel extends Model
{
    /**
     * email verifying otp table name
     *
     * @var string
     */
    private $userEmailOTPTable = 'user_email_otp';

    /**
     * set user email otp record
     *
     * @return void
     */
    public function setEmailVerifyingOTP($userId, $email, $otp)
    {
        $this
            ->data('user_id', $userId)
            ->data('email', $email)
            ->data('otp', $otp)
            ->data('sent', time())
            ->insert($this->userEmailOTPTable);
    }

    /**
     * get User Id By Email
     *
     * @var string $email
     * @return int %id
     */
    public function getUserIdByEmail($email)
    {
        return $this->select('user_id')
            ->from($this->userEmailOTPTable)
            ->where('email = ?', $email)
            ->fetch()->user_id;
    }

    /**
     * get otp By Email
     *
     * @var string $email
     * @return int %id
     */
    public function getEmailOTP($email)
    {
        return $this->select('otp')
            ->from($this->userEmailOTPTable)
            ->where('email = ?', $email)
            ->fetch()->otp;
    }
}
