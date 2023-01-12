<div class="password-page profile-sub-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li><a href="<?php echo url('/profile/data'); ?>" data-translationKey="dataManagement">Data Management</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="editPassword">Edit Password</li>
		</ol>
	</div>
	<h2 data-translationKey="editPassword">Edit Password</h2>
	<div class="form-box">
		<div id="js-response-container" class="response-container">Be sure to click the Save Changes button when you are done.</div>
		<form id="js-profile-edit-password-form" action="<?php echo url('/profile/data/password/edit'); ?>" method="POST">
			<span class="form-info">Be sure to click the <strong>Save Changes</strong> button when you are done.</span>
			<div class="current-password-box">
				<label for="current-password">Current password</label>
				<input type="password" dir="auto" name="current-password" id="current-password" minlength="8" required />
			</div>
			<div class="new-password-box">
				<label for="new-password">New password</label>
				<input type="password" dir="auto" name="new-password" id="new-password" minlength="8" required />
			</div>
			<div class="confirm-new-password-box">
				<label for="confirm-new-password">Confirm new password</label>
				<input type="password" dir="auto" name="confirm-new-password" id="confirm-new-password" minlength="8" required />
			</div>
			<div class="submit-btn">
				<button type="submit">Save changes</button>
			</div>
		</form>
	</div>
</div>