<!-- Login Page -->
<div class="login">

  <div class="header">

    <div class="logo">
      <a href="<?php echo url("/") ?>">
        <img src="<?php echo assets('common/images/logo.png') ?>" alt="logo" />
      </a>
    </div>

  </div>

  <div class="form-box">

    <div class="response-container" id="js-response-container"></div>

    <div class="head" data-translationKey="signIn">Sign-In</div>

    <form id="js-login-form" action="<?php echo url('/login/submit'); ?>" method="POST">

      <div class="email-box">
        <label for="email" data-translationKey="emailOrPhoneNumber">Email or phone number</label>
        <input type="email" dir="ltr" name="email" id="email" maxlength="128" required />
        <span class="test-note">for testing use: test@mail.com</span>
      </div>

      <div class="password-box">
        <label for="password" data-translationKey="password">Password</label>
        <input type="password" dir="auto" name="password" id="password" maxlength="128" required />
        <span class="test-note">for testing use: 12345678</span>
      </div>

      <input type="hidden" name="form-token" value="<?php echo $token ?>" />

      <div class="submit-btn">
        <button type="submit" data-translationKey="continue">Continue</button>
      </div>

      <div class="keep-signed-box">
        <input type="checkbox" name="keep-signed-in" id="keep-signed-in"></input>
        <span data-translationKey="keepMeSignedIn">Keep me signed in</span>
      </div>

    </form>

    <div class="privacy-box">
      <span data-translationKey="loginPrivacyHint">By continuing, you agree to Store's</span>
      <a href="" data-translationKey="conditionsOfUse">Conditions of Use</a>
      <span data-translationKey="and">and</span>
      <a href="" data-translationKey="privacyNotice">Privacy Notice</a>
    </div>

    <div class="help-box">

      <div class="btn" id="js-login-help-btn">
        <span data-translationKey="needHelp?">Need help?</span>
      </div>
      <div class="actions" id="js-login-help-actions">
        <a href="" data-translationKey="ForgetYourPassword">Forget your password?</a>
        <a href="" data-translationKey="OtherIssuesWithSignIn">Other issues with Sign-In?</a>
      </div>
    </div>

  </div>

  <div class="divider">
    <h5 data-translationKey="newToStore">New to Store?</h5>
    <span></span>
  </div>

  <div class="create-account">
    <button>
      <a href="<?php echo url("/register") ?>" data-translationKey="CreateYourStoreAccount">Create your Store account</a>
    </button>
  </div>

</div>