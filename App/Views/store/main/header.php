<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?></title>
  <!-- fav icon override -->
  <link rel="shortcut icon" href="<?php echo assets('common/images/favicon.ico') ?>" type="image/x-icon">
  <link rel="icon" href="<?php echo assets('common/images/favicon.ico') ?>" type="image/x-icon">
  <!-- font awesome -->
  <link rel="styleSheet" href="<?php echo assets('common/css/font_awesome/css/all.min.css') ?>">
  <!-- custom css files -->
  <link rel="styleSheet" href="<?php echo assets('common/css/global.css') ?>">
  <link rel="styleSheet" href="<?php echo assets('common/css/animation.css') ?>">
  <link rel="styleSheet" href="<?php echo assets('store/css/header.css') ?>">
  <link rel="styleSheet" href="<?php echo assets('store/css/navbar.css') ?>">
  <link rel="styleSheet" href="<?php echo assets('store/css/footer.css') ?>">

  <?php
  // just import current page css file only
  $html = '<link rel="styleSheet" href="?path">';

  if ($path === '/' || str_starts_with($path, '/home')) {
    echo inject_html($html, ['path' => assets('store/css/home.css')]);
  } else if (str_starts_with($path, '/login')) {
    echo inject_html($html, ['path' => assets('common/css/login.css')]);
  } else if (str_starts_with($path, '/register')) {
    echo inject_html($html, ['path' => assets('common/css/register.css')]);
  } else if (str_starts_with($path, '/email-verifying')) {
    echo inject_html($html, ['path' => assets('common/css/email-verifying.css')]);
  } else if ($path === '/profile') {
    echo inject_html($html, ['path' => assets('store/css/profile.css')]);
  } else if (str_starts_with($path, '/profile/data')) {
    echo inject_html($html, ['path' => assets('store/css/profile-data.css')]);
  } else if (str_starts_with($path, '/profile/addresses')) {
    echo inject_html($html, ['path' => assets('store/css/addresses.css')]);
  } else if (str_starts_with($path, '/profile/orders')) {
    echo inject_html($html, ['path' => assets('store/css/orders.css')]);
  } else if (str_starts_with($path, '/product')) {
    echo inject_html($html, ['path' => assets('store/css/product.css')]);
  } else if (str_starts_with($path, '/cart')) {
    echo inject_html($html, ['path' => assets('store/css/cart.css')]);
  } else if (str_starts_with($path, '/category')) {
    echo inject_html($html, ['path' => assets('store/css/category.css')]);
  } else if (str_starts_with($path, '/support/chat')) {
    echo inject_html($html, ['path' => assets('store/css/chat.css')]);
  } else if (str_starts_with($path, '/search')) {
    echo inject_html($html, ['path' => assets('store/css/search.css')]);
  }else if (str_starts_with($path, '/payment')) {
    echo inject_html($html, ['path' => assets('store/css/payment.css')]);
  }
  ?>

  <!-- normalize file -->
  <link rel="styleSheet" href="<?php echo assets('common/css/normalize.css') ?>">
  <!-- google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap">
</head>

<body>

  <!------------------------ start screen covers ----------------------------->
  <div class="screen-cover" id="js-screen-cover"></div>
  <div class="content-cover" id="js-content-cover"></div>
  <div class="loading-screen" id="js-loading-screen">
    <div class="circular-progress"></div>
  </div>
  <!------------------------ end screen covers ----------------------------->