<?php

namespace App\Controllers\Admin;

use System\Controller;

class SettingsController extends Controller
{
    /**
     * Display Settings Form
     *
     * @return mixed
     */
    public function index()
    {
        $this->html->setTitle('Settings');

        $settingsModel = $this->load->model('Settings');

        $data['settings'] = $settingsModel->settings();

        $view = $this->view->render('admin/settings/settings', $data);

        return $this->adminLayout->render($view);
    }

    /**
     * Submit for editing settings
     *
     * @return string | json
     */
    public function save()
    {
        if (!$this->isEditInputDataValid()) {
            $json['success'] = false;
            $json['message'] = flatten($this->validator->getErrors());
            return json_encode($json);
        }

        $settingsModel = $this->load->model('Settings');

        $settingsModel->update();

        $json['success'] = true;
        $json['message'] = 'Settings updated successfully';

        return json_encode($json);
    }

    /**
     * Validate the form
     *
     * @param int $id
     * @return bool
     */
    private function isEditInputDataValid()
    {

        $this->validator->required('nav-announcement')->text('nav-announcement')->maxLen('nav-announcement', 200);
        $this->validator->required('site-name')->text('site-name')->maxLen('site-name', 200);
        $this->validator->required('site-email')->email('site-email')->maxLen('site-email', 200);
        $this->validator->required('site-close-msg')->text('site-close-msg')->maxLen('site-close-msg', 200);
        $this->validator->required('site-status')->isFormatIntBool('site-status');

        return $this->validator->passes();
    }
}
