<div class="register">

  <div class="header">

    <div class="logo">
      <a href="<?php echo url('/'); ?>">
        <img src="<?php echo assets('common/images/logo.png'); ?>" alt="logo" />
      </a>
    </div>

  </div>

  <div class="form-box">
    
  <div class="response-container" id="js-response-container"></div>

    <div class="head">Create account</div>

    <form id="js-register-form" action="<?php echo url('/register/submit'); ?>" method="POST">

      <div class="first-name-box">
        <label for="first-name">First name</label>
        <input type=" text" dir="auto" name="first-name" id="first-name" value="<?php echo isset($firstName) ? $firstName : '' ?>" maxlength="128" required />
      </div>

      <div class="last-name-box">
        <label for="last-name">Last name</label>
        <input type="text" dir="auto" name="last-name" id="last-name" value="<?php echo isset($lastName) ? $lastName : '' ?>" maxlength="128" required />
      </div>

      <div class="email-box">
        <label for="email"">Email</label>
        <input type=" email" dir="ltr" name="email" id="email" value="<?php echo isset($email) ? $email : '' ?>" maxlength="128" required />
      </div>

      <div class="phone-box">
        <label for="phone">Phone number</label>
        <div class="country-code">
          <span id="js-country-code-display">DZ +213</span>
          <?php readfile(url("App/Views/common/phone-codes.php")); ?>
        </div>
        <input type="tel" dir="ltr" name="phone" id="phone" maxlength="32" required>
      </div>

      <div class="password-box">
        <label for="password"">Password</label>
        <input type=" password" dir="auto" name="password" id="password" maxlength="128" minlength="8" placeholder="At least 8 characters" required />
      </div>

      <div class="confirm-password-box">
        <label for="confirm-password"">Confirm password</label>
        <input type=" password" dir="auto" name="confirm-password" id="confirm-password" maxlength="128" minlength="8" required />
      </div>

      <input type="hidden" name="form-token" value="<?php echo $token ?>" />

      <div class="submit-btn">
        <button type="submit">Verify email</button>
      </div>

    </form>

    <div class="privacy-box">
      By creating an account, you agree to Store's
      <a href="">Conditions of Use</a>
      and
      <a href="">Privacy Notice</a>
    </div>

    <div class="already-member">
      Already have an account?
      <a href="<?php echo url("/login") ?>">Sign-In</a>
    </div>
  </div>

</div>