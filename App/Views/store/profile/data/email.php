<div class="email-page profile-sub-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li><a href="<?php echo url('/profile/data'); ?>" data-translationKey="dataManagement">Data Management</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="editEmail">Edit Email</li>
		</ol>
	</div>
	<h2 data-translationKey="editEmail">Edit Email</h2>
	<div class="form-box">
		<div id="js-response-container" class="response-container"></div>
		<form id="js-profile-edit-email-form" action="<?php echo url('/profile/data/email/verify'); ?>" method="POST">
			<span class="form-info">Current email address: <strong><?php echo isset($email) ? $email : '' ?></strong></span>
			<span class="form-info">Enter the new email address you would like to associate with your account below. We will send a One Time Password (OTP) to that address.</span>
			<div class="email-box">
				<label for="email">New email</label>
				<input type="email" name="email" id="email" maxlength="96" required />
			</div>
			<div class="submit-btn">
				<button type="submit">Verify email</button>
			</div>
		</form>
	</div>
</div>