<?php

// download route
$app->route->add('/download', 'Common/Download');

// auth routes
$app->route->add('/register', 'Common/Auth/Register');
$app->route->add('/register/submit', 'Common/Auth/Register@submit', 'POST');
$app->route->add('/email-verifying', 'Common/Auth/EmailVerifying');
$app->route->add('/email-verifying/submit', 'Common/Auth/EmailVerifying@submit', 'Post');
$app->route->add('/email-verifying/resend-otp', 'Common/Auth/EmailVerifying@resendOTP');
$app->route->add('/login', 'Common/Auth/Login');
$app->route->add('/login/submit', 'Common/Auth/Login@submit', 'POST');
$app->route->add('/logout', 'Common/Auth/Logout');

// Not Found Routes
$app->route->add('/404', 'Common/NotFound');
$app->route->notFound('/404');