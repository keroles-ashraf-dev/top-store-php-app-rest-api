<?php

// auth
$app->route->add('/api/v1/register', 'Api/V1/Auth/Register', 'POST');
$app->route->add('/api/v1/login', 'Api/V1/Auth/Login', 'POST');
$app->route->add('/api/v1/verify-email', 'Api/V1/Auth/EmailVerifying', 'GET');
$app->route->add('/api/v1/resend-otp', 'Api/V1/Auth/EmailVerifying@resendOTP', 'POST');

// user
$app->route->add('/api/v1/user', 'Api/V1/User', 'GET');
$app->route->add('/api/v1/user', 'Api/V1/User@create', 'POST');
$app->route->add('/api/v1/user', 'Api/V1/User@update', 'PUT');
$app->route->add('/api/v1/user', 'Api/V1/User@delete', 'DELETE');

// cart
$app->route->add('/api/v1/cart', 'Api/V1/Cart', 'GET');
$app->route->add('/api/v1/cart/increase', 'Api/V1/Cart@increment', 'Post');
$app->route->add('/api/v1/cart/decrease', 'Api/V1/Cart@decrement', 'Post');
$app->route->add('/api/v1/cart/add', 'Api/V1/Cart@add', 'Post');

// Address
$app->route->add('/api/v1/Addresses', 'Api/V1/Addresses', 'GET');
$app->route->add('/api/v1/Address', 'Api/V1/Addresses@getAddress', 'GET');

// products
$app->route->add('/api/v1/category-products', 'Api/V1/Categories@getCategoryProducts', 'GET');
$app->route->add('/api/v1/product', 'Api/V1/Products', 'GET');

// deals
$app->route->add('/api/v1/deals', 'Api/V1/Deals', 'GET');
$app->route->add('/api/v1/deal', 'Api/V1/Deals@getDeal', 'GET');

// carousel
$app->route->add('/api/v1/main-carousel', 'Api/V1/Carousel', 'GET');

// categories
$app->route->add('/api/v1/parents-categories', 'Api/V1/Categories', 'GET');
