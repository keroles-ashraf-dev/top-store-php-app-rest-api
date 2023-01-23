<?php

use System\Application;

$app = Application::getInstance();
$requestedUrl = $app->request->url(); // requested route url

if (strpos($requestedUrl, '/api/v1') === 0) {
    // set default headers just in case exception thrown
    $app->api->setHeaders();

    // check if request has api key
    $app->route->addMiddleWare(function ($app) {
        $app->load->action('Api/V1/MiddleWares/Access', 'index');
    });

    // check if requested route needs auth token
    //if so, then add auth token middle ware
    if ($app->security->isAuthTokenNeeded($requestedUrl)) {

        $app->route->addMiddleWare(function ($app) {
            $app->load->action('Api/V1/MiddleWares/Access', 'isValidAuthToken');
        });
    }

    // check if requested route needs access rights
    // if so, then add access rights middle ware
    if ($app->security->isAccessRightsNeeded($requestedUrl)) {

        $app->route->addMiddleWare(function ($app) {
            $app->load->action('Api/V1/MiddleWares/Access', 'hasAccessRights');
        });
    }

    // share|load settings for each request
    $app->share('settings', function ($app) {

        $settingsModel = $app->load->model('Settings');
        $settingsModel->settings();
        return $settingsModel;
    });

    // add api routes
    include('routes/api/v1/api.php');
} else if (strpos($requestedUrl, '/admin') === 0) {
    // check if the current url started with /admin
    // if so, then add admin access middle ware
    $app->route->addMiddleWare(function ($app) {
        $app->load->action('Admin/MiddleWares/Access', 'index');
    });

    // share admin layout
    $app->share('adminLayout', function ($app) {
        return $app->load->controller('Admin/Main/Layout');
    });

    // add admin routes
    include('routes/web/admin.php');
    include('routes/web/common.php');
} else {
    // check if requested route needs login
    // if so, then add login middle ware
    if ($app->security->isLoginNeeded($requestedUrl)) {

        $app->route->addMiddleWare(function ($app) {
            $app->load->action('Store/MiddleWares/Access', 'index');
        });
    }

    // share|load settings for each request
    $app->share('settings', function ($app) {

        $settingsModel = $app->load->model('Settings');
        $settingsModel->settings();
        return $settingsModel;
    });

    // share store user layout
    $app->share('storeLayout', function ($app) {
        return $app->load->controller('Store/Main/Layout');
    });

    // add store routes
    include('routes/web/store.php');
    include('routes/web/common.php');
}
