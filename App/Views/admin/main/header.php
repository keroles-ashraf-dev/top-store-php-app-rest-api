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
  <!-- normalize file -->
  <link rel="styleSheet" href="<?php echo assets('common/css/normalize.css') ?>">
  <!-- custom css files -->
  <link rel="styleSheet" href="<?php echo assets('common/css/global.css') ?>">
  <link rel="styleSheet" href="<?php echo assets('common/css/animation.css') ?>">
  <link rel="styleSheet" href="<?php echo assets('admin/css/header.css') ?>">
  <link rel="styleSheet" href="<?php echo assets('admin/css/navbar.css') ?>">
  <link rel="styleSheet" href="<?php echo assets('admin/css/sidebar.css') ?>">
  <link rel="styleSheet" href="<?php echo assets('admin/css/footer.css') ?>">
  <!-- google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap">

  <?php
  // just import only current page css file
  $html = '<link rel="styleSheet" href="?path">';

  if (str_starts_with($path, '/admin/users')) {
    echo inject_html($html, ['path' => assets('admin/css/users.css')]);
  } else if (str_starts_with($path, '/admin/categories')) {
    echo inject_html($html, ['path' => assets('admin/css/categories.css')]);
  } else if (str_starts_with($path, '/admin/products')) {
    echo inject_html($html, ['path' => assets('admin/css/products.css')]);
  } else if (str_starts_with($path, '/admin/offers') || $path == '/admin') {
    echo inject_html($html, ['path' => assets('admin/css/offers.css')]);
  } else if (str_starts_with($path, '/admin/languages')) {
    echo inject_html($html, ['path' => assets('admin/css/languages.css')]);
  } else if (str_starts_with($path, '/admin/settings')) {
    echo inject_html($html, ['path' => assets('admin/css/settings.css')]);
  }else if (str_starts_with($path, '/admin/support/chat')) {
    echo inject_html($html, ['path' => assets('admin/css/chat.css')]);
  }

  ?>

</head>

<body>
  <!------------------------ start screen covers ----------------------------->
  <div class="screen-cover" id="js-screen-cover"></div>
  <div class="content-cover" id="js-content-cover"></div>