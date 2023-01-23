<?php

namespace App\Models;

use System\Model;

class LoginModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * auth tokens table name
     *
     * @var string
     */
    private $authTokensTable = 'auth_tokens';

    /**
     * Logged In User
     *
     * @var \stdClass
     */
    private $user;

    /**
     * Determine if the given login data is valid
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function isValidLogin()
    {
        $email = $this->request->fileGetContents("email");
        $password = $this->request->fileGetContents("password");

        $user = $this->where('email=?', $email)->fetch($this->table);

        if (!$user) {
            return false;
        }

        $isPasswordValid = password_verify($password, $user->password);

        $this->user = $user;

        return $isPasswordValid;
    }


    /**
     * Determine whether the user is logged in
     *
     * @return bool
     */
    public function isLogged()
    {
        $token = '';

        if ($this->cookie->has('auth-token')) {
            $token = $this->cookie->get('auth-token');
        } elseif ($this->session->has('auth-token')) {
            $token = $this->session->get('auth-token');
        } else {
            return false;
        }

        $user = $this->select('user_id')->where('token=?', $token)->fetch($this->authTokensTable);

        if (!$user) {
            return false;
        }

        $user = $this->select('*')->where('id=?', $user->user_id)->fetch($this->table);
        $user->token = $token;

        if (!$user) {
            return false;
        }

        $this->user = $user;

        return true;
    }

    /**
     * Determine whether the auth token from rest api request is valid
     * 
     * @param string $token
     * @return bool
     */
    public function isTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $userId = $this->select('user_id')->where('token=?', $token)->fetch($this->authTokensTable)->user_id;

        if (empty($userId)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the auth token from rest api request is valid for passed user id
     * 
     * @param string $token
     * @param string $id
     * @return bool
     */
    public function isTokenValidForUserId($userToken, $userId)
    {
        $id = $this->select('user_id')->where('token=?', $userToken)->fetch($this->authTokensTable)->user_id;

        if ($id == $userId) {
            return true;
        }

        return false;
    }

    /**
     * set auth token to user record
     *
     * @return void
     */
    public function setAuthToken($token)
    {
        $this->data('token', $token)->where('user_id=?', $this->user->id)->update($this->authTokensTable);
        $this->user->token = $token;
    }

    /**
     * Get Logged In User data
     *
     * @return \stdClass
     */
    public function user()
    {
        return $this->user;
    }
}
