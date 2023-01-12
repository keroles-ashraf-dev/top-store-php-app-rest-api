<!-- Login Page -->
<div class="email-verifying">

  <div class="header">

    <div class="logo">
      <a href="<?php echo url("/") ?>">
        <img src="<?php echo assets('common/images/logo.png') ?>" alt="logo" />
      </a>
    </div>

  </div>

  <div class="form-box">
    
    <div class="response-container" id="js-response-container"></div>

    <div class="head">Verify email address</div>

    <div class="sub-head">
      To verify your email, we've sent a One Time Password (OTP) to
      <span><?php echo $email ?></span>
      <a href="<?php echo url("/register") ?>">Change</a>
    </div>

    <form id="js-email-verifying-form" action="<?php echo url('/email-verifying/submit'); ?>" method="POST">

      <div class="otp-box">
        <label for="email-verifying-otp"">Enter OTP</label>
        <input type="number" dir="ltr" name="email-verifying-otp" maxlength="128" required />
      </div>

      <div class="new-otp-box" id="js-new-otp-box">
        <i class="fa-solid fa-circle-check"></i>
        <span>A new code has been sent to your email.</span>
      </div>

      <input type="hidden" name="form-token" value="<?php echo $token ?>" />

      <div class="submit-btn">
        <button type="submit">Verify</button>
      </div>

    </form>

    <div class="resend-otp-box">
      <button id="js-resend-otp" data-url="<?php echo url('/email-verifying/resend-otp'); ?>">Resend OTP</button>
    </div>

    <div class="note-box">
      Note: If you didn't receive our verification email:

      <li>Confirm that your email address was entered correctly above.</li>
      <li>Check your spam or junk email folder.</li>
    </div>

  </div>

</div>