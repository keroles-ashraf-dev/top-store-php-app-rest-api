<?php

namespace App\Controllers\Api\V1\MiddleWares;

use System\Controller;

class AccessController extends Controller
{
    /**
     * Check request api key validation
     *
     * @return void
     */
    public function index()
    {
        $headers = $this->api->getHeaders();

        $apiKey = $headers['api-key'] ?? '';

        // api key is empty so send bad request request
        if (empty($apiKey)) {
            $res['success'] = 0;
            $res['message'] = 'Api key is invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $apiModel = $this->load->model('ApiKeys');

        $isValid =  $apiModel->isValidKey($apiKey);

        // api key is not valid so send unauthorized request
        if (!$isValid) {
            $res['success'] = 0;
            $res['message'] = 'Api key is invalid';

            $this->api->setHeaders()->unauthorized($res);
        }
    }

    /**
     * Check request auth token validation
     *
     * @return void
     */
    public function isValidAuthToken()
    {
        $headers = $this->api->getHeaders();

        $token = $headers['authorization'] ?? '';

        // token is empty so send unauthorized request
        if (empty($token)) {
            $res['success'] = 0;
            $res['message'] = 'Auth token is invalid';
            $res['data'] = $token;

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
    }

    /**
     * Check if request auth token and passed user id has rights to access that content
     *
     * @return void
     */
    public function hasAccessRights()
    {
        $headers = $this->api->getHeaders();

        $token = $headers['authorization'];

        $userId = $this->request->get('user-id') ?: $this->request->fileGetContents('user-id');

        if (!is_numeric($userId)) {

            $res['success'] = 0;
            $res['message'] = 'User id is invalid';

            $this->api->setHeaders()->badRequest($res);
        }

        $loginModel = $this->load->model('Login');

        $hasAccessRights = $loginModel->isTokenValidForUserId($token, $userId);

        if (!$hasAccessRights) {

            $res['success'] = 0;
            $res['message'] = 'You do not have access rights to that content';

            $this->api->setHeaders()->forbidden($res);
        }
    }
}
