<div class="phone-page profile-sub-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li><a href="<?php echo url('/profile/data'); ?>" data-translationKey="dataManagement">Data Management</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="editPhone">Edit Phone</li>
		</ol>
	</div>
	<h2 data-translationKey="editPhone">Edit Phone</h2>
	<div class="form-box">
		<div id="js-response-container" class="response-container">Be sure to click the Save Changes button when you are done.</div>
		<form id="js-profile-edit-phone-form" action="<?php echo url('/profile/data/phone/edit'); ?>" method="POST">
		<span class="form-info">Current phone number: <strong><?php echo isset($phone) ? $phone : '' ?></strong></span>	
		<span class="form-info">Be sure to click the <strong>Save Changes</strong> button when you are done.</span>
			<div class="phone-box">
				<label for="phone">New phone number</label>
				<div class="country-code">
					<span id="js-country-code-display">DZ +213</span>
					<?php readfile(url("App/Views/common/phone-codes.php")); ?>
				</div>
				<input type="tel" dir="ltr" name="phone" id="phone" maxlength="32" required />
			</div>
			<div class="submit-btn">
				<button type="submit">Save changes</button>
			</div>
		</form>
	</div>
</div>