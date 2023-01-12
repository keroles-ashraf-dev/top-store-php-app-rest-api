<div class="email-otp-page profile-sub-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li><a href="<?php echo url('/profile/data'); ?>" data-translationKey="dataManagement">Data Management</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li><a href="<?php echo url('/profile/data/email'); ?>" data-translationKey="dataManagement">Edit Email</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="emailOtp">Email OTP</li>
		</ol>
	</div>
	<h2 data-translationKey="editEmail">Verify Email OTP</h2>
	<div class="form-box">
		<div id="js-response-container" class="response-container"></div>
		<form id="js-profile-verify-email-otp-form" action="<?php echo url('/profile/data/email/verify-submit'); ?>" method="POST">
			<span class="form-info">To verify your new email, we've sent a One Time Password (OTP) to <strong><?php echo isset($email) ? $email : '' ?></strong></span>
			<div class="email-box">
				<label for="email-otp">OTP</label>
				<input type="email-otp" name="email-otp" id="email-otp" maxlength="7" required />
			</div>
			<div class="submit-btn">
				<button type="submit">Verify email OTP</button>
			</div>
		</form>
	</div>
</div>