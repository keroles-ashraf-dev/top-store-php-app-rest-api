<div class="nav">
  <div class="main-nav padding-container">
    <div class="main-nav-start">
      <div class="logo">
        <a href="<?php echo url("/") ?>">
          <img src="<?php echo assets('common/images/logo.png') ?>" alt="logo" />
        </a>
      </div>
      <div id="js-deliver-box" class="deliver-box">
        <a href="<?php echo url("/profile/addresses") ?>">
          <i class="fa-solid fa-location-dot"></i>
          <div class="location">
            <span data-translationKey="deliverTo">Deliver to</span>
            <span><?php echo $address->city ?? $address ?></span>
          </div>
        </a>
      </div>
    </div>
    <div class="main-nav-end">
      <div class="settings-box" id="js-nav-settings-box">
        <span class="settings-label" id="js-nav-settings-box-label">EN</span>
        <div class="settings-dropdown">
          <?php
          $html = '
          <div>
          <input type="radio" id="?code" value="?code" name="language-option">
          <label for="?code">?name</label>
          </div>
          ';
          foreach ($languages as $language) {
            $values = ['code' => $language->code, 'name' => ucfirst($language->name)];
            echo inject_html($html, $values);
          }
          ?>
        </div>
      </div>
      <div id="js-nav-account-box" class="account-box">
        <a href="<?php echo url("/profile") ?>">
          <i class="fa-solid fa-user fa-lg"></i>
        </a>
        <div class="account-dropdown">
          <?php if (isset($user)) : ?>
            <span><?php echo ucfirst($user->first_name) . ' ' . ucfirst($user->last_name) ?></span>
            <span><?php echo $user->email ?></span>
            <a href="<?php echo url('/logout') ?>">
              <button data-translationKey="signOut">Sign out</button>
            </a>
          <?php else : ?>
            <a href="<?php echo url('/login') ?>">
              <button data-translationKey="login">Login</button>
            </a>
            <div class="not-member">
              <span>Not a member?</span>
              <a href="<?php echo url('/register') ?>">Sign up</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div id="js-nav-cart-box" class="cart-box">
        <a href="<?php echo url("/cart") ?>">
          <i class="fa-solid fa-cart-shopping fa-lg"></i>
          <div>
            <span id="js-nav-cart-box-count"><?php echo $cartCount ?></span>
          </div>
        </a>
      </div>
    </div>
  </div>
  <div class="sub-nav padding-container">
    <div class="links">
      <?php
      $html = '<a href="?categoryViewUrl"><span>?name</span></a>';
      foreach ($parentCategories as $c) {
        $values = [
          'name' => $c->name,
          'categoryViewUrl' => url('category?id=') . $c->id,
        ];
        echo inject_html($html, $values);
      }
      ?>
    </div>
    <div id="js-nav-ad" class="nav-ad">
      <span><?php echo $navAnnouncement ?></span>
    </div>
  </div>
  <div class="search-bar" id="js-nav-search-container">
    <form role="search" action="<?php echo url('/search') ?>" method="GET">
      <div class="dropdown">
        <select id="js-categories-select" name="category-id">
          <option <?php echo ((empty($categoryId) || $categoryId == 'null') ? 'selected' : '') ?> data-name="All" value="null">All</option>
          <?php
          foreach ($categories as $c) {
            $html = '<option ' . ($categoryId == $c->id ? 'selected ' : '') . 'value="?id" data-name="?name">?name</option>';
            echo inject_html($html, ['id' => $c->id, 'name' => ucfirst($c->name)]);
          }
          ?>
        </select>
        <span id="js-categories-select-display">All</span>
        <i class="fa-solid fa-caret-down"></i>
      </div>
      <input type="text" class="input" id="js-nav-search-input" dir="auto" name="keyword" type="text" autocomplete="off" value="<?php echo (empty($keyword) ? '' : $keyword) ?>" />
      <button class="submit" type="submit">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
    </form>
  </div>
  <div class="hamburger-menu">
    <div class="btn" id="js-hamburger-menu-btn">
      <i class="fa-solid fa-bars fa-lg"></i>
    </div>
    <div class="content" id="js-hamburger-menu-content">
      <div class="data-box">
        <i class="fa-solid fa-user"></i>
        <span>Hello, <?php echo ucfirst($user->first_name ?? '') ?></span>
      </div>
      <div class="account-box">
        <a href="<?php echo url('/profile') ?>">Your Account</a>
        <a href="<?php echo url('/cart') ?>">Your Cart</a>
        <a href="<?php echo url('/profile/orders') ?>">Your Orders</a>
        <a href="<?php echo url('/support/chat') ?>">Contact Us</a>
        <?php if (isset($user)) : ?>
          <a href="<?php echo url('/logout') ?>">Sign Out</a>
        <?php else : ?>
          <a href="<?php echo url('/login') ?>">Sign In</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>