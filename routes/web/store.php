<?php

// home routes
$app->route->add('/', 'Store/Home');
$app->route->add('/home', 'Store/Home');

// profile routes
$app->route->add('/profile', 'Store/Profile');

// profile -> orders
$app->route->add('/profile/orders', 'Store/Orders');
$app->route->add('/profile/orders/create', 'Store/Orders@create', 'POST');
$app->route->add('/profile/orders/cancel', 'Store/Orders@cancel', 'POST');

// profile -> data
$app->route->add('/profile/data', 'Store/ProfileData');
$app->route->add('/profile/data/name', 'Store/ProfileData@displayChangeNamePage');
$app->route->add('/profile/data/name/edit', 'Store/ProfileData@saveNameChanges', 'POST');
$app->route->add('/profile/data/email', 'Store/ProfileData@displayChangeEmailPage');
$app->route->add('/profile/data/email/verify', 'Store/ProfileData@sendEmailOTP', 'POST');
$app->route->add('/profile/data/email/verify-otp', 'Store/ProfileData@displayVerifyEmailPage');
$app->route->add('/profile/data/email/verify-submit', 'Store/ProfileData@verifyEmailOtp', 'POST');
$app->route->add('/profile/data/phone', 'Store/ProfileData@displayChangePhonePage');
$app->route->add('/profile/data/phone/edit', 'Store/ProfileData@savePhoneChanges', 'POST');
$app->route->add('/profile/data/password', 'Store/ProfileData@displayChangePasswordPage');
$app->route->add('/profile/data/password/edit', 'Store/ProfileData@savePasswordChanges', 'POST');
// profile -> addresses
$app->route->add('/profile/addresses', 'Store/Addresses');
$app->route->add('/profile/addresses/add', 'Store/Addresses@displayAddPage');
$app->route->add('/profile/addresses/add/save', 'Store/Addresses@addAddress', 'POST');
$app->route->add('/profile/addresses/edit', 'Store/Addresses@displayEditPage');
$app->route->add('/profile/addresses/edit/save', 'Store/Addresses@editAddress', 'POST');
$app->route->add('/profile/addresses/remove', 'Store/Addresses@removeAddress', 'POST');
$app->route->add('/profile/addresses/set-default', 'Store/Addresses@setDefault', 'POST');

// category
$app->route->add('/category', 'Store/Categories');

// product
$app->route->add('/product', 'Store/Products');

// cart
$app->route->add('/cart', 'Store/Cart');
$app->route->add('/product/add-to-cart', 'Store/Cart@add', 'POST');
$app->route->add('/product/increment', 'Store/Cart@increment', 'POST');
$app->route->add('/product/decrement', 'Store/Cart@decrement', 'POST');

// chatting route
$app->route->add('/support/chat', 'Store/Chat');
$app->route->add('/support/chat/socket', 'Store/Chat@websocketConnect', 'POST');

// search
$app->route->add('/search', 'Store/Search');

// payment
$app->route->add('/payment', 'Store/Payment');