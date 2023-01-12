<?php

namespace App\Controllers\Store;

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
        $user = $this->load->model('Login')->user();

        $chatsList = $chatModel->getChatList($user->id);

        if (empty($chatsList)) {
            $chatModel->createChannel($user->id);
            $chatsList = $chatModel->getChatList($user->id);
        }

        $data['chatsList'] = $chatsList;
        $data['userId'] = $loginModel->user()->id;
        $data['token'] = $loginModel->user()->token;
        $data['channelId'] = $channelId;
        $data['messages'] = empty($channelId) ? [] : $chatModel->getMessages($channelId);

        // if theres no channel id in $_GET and channels list has just one channel then display it
        if (empty($channelId) && count($chatsList) == 1) {
            $data['channelId'] = $chatsList[0]->id;
            $data['messages'] = $chatModel->getMessages($channelId);;
        }

        $this->html->setTitle('Support|Chat');

        // disable footer
        //$this->storeLayout->disable('footer');

        $view = $this->view->render('store/support/chat', $data);

        $this->app->cookie->set('temp-id', $loginModel->user()->id, 0.002);

        return $this->storeLayout->render($view);
    }
}
