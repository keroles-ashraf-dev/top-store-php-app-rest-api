<?php

// Admin Offers Routes
$app->route->add('/admin', 'Admin/Offers');
$app->route->add('/admin/offers', 'Admin/Offers');
// slider
$app->route->add('/admin/offers/add-slider', 'Admin/Offers@displayAddSliderForm');
$app->route->add('/admin/offers/add-slider/save', 'Admin/Offers@addSlider', 'POST');
$app->route->add('/admin/offers/delete-slider', 'Admin/Offers@deleteSlider', 'POST');
$app->route->add('/admin/offers/change-slider-status', 'Admin/Offers@changeSliderStatus', 'POST');
// deals
$app->route->add('/admin/offers/add-deal', 'Admin/Offers@displayAddDealForm');
$app->route->add('/admin/offers/add-deal/save', 'Admin/Offers@addDeal', 'POST');
$app->route->add('/admin/offers/delete-deal', 'Admin/Offers@deleteDeal', 'POST');
$app->route->add('/admin/offers/change-deal-status', 'Admin/Offers@changeDealStatus', 'POST');

$app->route->add('/admin/offers/edit/:id', 'Admin/Offers@displayEditForm', 'POST');
$app->route->add('/admin/offers/edit/save/:id', 'Admin/Offers@editProduct', 'POST');
$app->route->add('/admin/offers/add/save', 'Admin/Offers@addProduct', 'POST');

// admin users routes
$app->route->add('/admin/users', 'Admin/Users');
$app->route->add('/admin/users/search', 'Admin/Users@search', 'POST');
$app->route->add('/admin/users/delete', 'Admin/Users@delete', 'POST');
$app->route->add('/admin/users/change-status', 'Admin/Users@changeStatus', 'POST');
$app->route->add('/admin/users/edit/:id', 'Admin/Users@displayEditForm', 'POST');
$app->route->add('/admin/users/save/:id', 'Admin/Users@save', 'POST');

// Admin Categories Routes
$app->route->add('/admin/categories', 'Admin/Categories');
$app->route->add('/admin/categories/search', 'Admin/Categories@search');
$app->route->add('/admin/categories/delete', 'Admin/Categories@delete', 'POST');
$app->route->add('/admin/categories/change-status', 'Admin/Categories@changeStatus', 'POST');
$app->route->add('/admin/categories/edit/:id', 'Admin/Categories@displayEditForm', 'POST');
$app->route->add('/admin/categories/edit/save/:id', 'Admin/Categories@editCategory', 'POST');
$app->route->add('/admin/categories/add', 'Admin/Categories@displayAddForm');
$app->route->add('/admin/categories/add/save', 'Admin/Categories@addCategory', 'POST');

// Admin Products Routes
$app->route->add('/admin/products', 'Admin/Products');
$app->route->add('/admin/products/search', 'Admin/Products@search');
$app->route->add('/admin/products/delete', 'Admin/Products@delete', 'POST');
$app->route->add('/admin/products/change-status', 'Admin/Products@changeStatus', 'POST');
$app->route->add('/admin/products/edit/:id', 'Admin/Products@displayEditForm', 'POST');
$app->route->add('/admin/products/edit/save/:id', 'Admin/Products@editProduct', 'POST');
$app->route->add('/admin/products/add', 'Admin/Products@displayAddForm');
$app->route->add('/admin/products/add/save', 'Admin/Products@addProduct', 'POST');

// chatting route
$app->route->add('/admin/support/chat', 'Admin/Chat');

// Admin languages Routes
$app->route->add('/admin/languages', 'Admin/Languages');
$app->route->add('/admin/languages/delete', 'Admin/Languages@delete', 'POST');
$app->route->add('/admin/languages/change-status', 'Admin/Languages@changeStatus', 'POST');
$app->route->add('/admin/languages/edit/:id', 'Admin/Languages@displayEditForm', 'POST');
$app->route->add('/admin/languages/edit/save/:id', 'Admin/Languages@edit', 'POST');
$app->route->add('/admin/languages/add', 'Admin/Languages@displayAddForm');
$app->route->add('/admin/languages/add/save', 'Admin/Languages@add', 'POST');

// Admin settings Routes
$app->route->add('/admin/settings', 'Admin/Settings');
$app->route->add('/admin/settings/save', 'Admin/Settings@save', 'POST');