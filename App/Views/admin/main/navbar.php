<div class="nav">

  <div class="main-nav padding-container">

    <div class="main-nav-start">

      <div class="logo">
        <a href="<?php echo url("/") ?>">
          <img src="<?php echo assets('common/images/logo.png') ?>" alt="logo" />
        </a>
      </div>

      <div class="title">
        <a href="<?php echo url("/admin") ?>">CPanal</a>
      </div>

    </div>

    <div class="main-nav-end">

      <div id="js-nav-settings-box" class="settings-box">
        <span class="settings-label" id="js-nav-settings-box-label">EN</span>
        <div class="settings-dropdown">
          <?php
          foreach ($languages as $e) {

            $input = '<input type="radio" id="?code" value="?code" name="language-option">';
            $label = '<label for="?code">?name</label>';

            echo '<div>';
            echo inject_html($input, ['code' => $e->code]);
            echo inject_html($label, ['code' => $e->code, 'name' => ucfirst($e->name)]);
            echo '</div>';
          }
          ?>
        </div>
      </div>
      <div id="js-nav-account-box" class="account-box">
        <i class="fa-solid fa-user fa-lg"></i>
        <div class="account-dropdown">
          <span><?php echo ucfirst($user->first_name) . ' ' . ucfirst($user->last_name) ?></span>
          <span><?php echo $user->email ?></span>
          <a href="<?php echo url('/logout') ?>">
            <button data-translationKey="signOut">Sign out</button>
          </a>
        </div>
      </div>

    </div>

  </div>
</div>