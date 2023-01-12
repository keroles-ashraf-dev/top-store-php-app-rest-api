<?php

namespace App\Controllers\Admin;

use System\Controller;

class ChatController extends Controller
{
    /**
     * Display product page
     *
     * @return mixed
     */
    public function index()
    {
        $channelId = $this->request->get('channelId', '');

        $chatModel = $this->load->model('Chat');
        $loginModel = $this->load->model('Login');

        $chatsList = $chatModel->getChatList();

        $data['chatsList'] = $chatsList;
        $data['adminId'] = $loginModel->user()->id;
        $data['channelId'] = $channelId;
        $data['messages'] = empty($channelId) ? [] : $chatModel->getMessages($channelId);

        $this->html->setTitle('Support|Chat');

        $view = $this->view->render('admin/support/chat', $data);

        $this->app->cookie->set('temp-id', $loginModel->user()->id, 0.002);

        return $this->adminLayout->render($view);
    }
}
