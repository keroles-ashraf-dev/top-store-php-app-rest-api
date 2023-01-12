<div class="name-page profile-sub-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li><a href="<?php echo url('/profile/data'); ?>" data-translationKey="dataManagement">Data Management</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="editName">Edit Name</li>
		</ol>
	</div>
	<h2 data-translationKey="editName">Edit Name</h2>
	<div class="form-box">
		<div id="js-response-container" class="response-container">Be sure to click the Save Changes button when you are done.</div>
		<form id="js-profile-edit-name-form" action="<?php echo url('/profile/data/name/edit'); ?>" method="POST">
			<span class="form-info">Be sure to click the <strong>Save Changes</strong> button when you are done.</span>
			<div class="first-name-box">
				<label for="first-name">First name</label>
				<input type="text" dir="auto" name="first-name" id="first-name" value="<?php echo isset($firstName) ? $firstName : '' ?>" maxlength="128" required />
			</div>
			<div class="last-name-box">
				<label for="last-name">Last name</label>
				<input type="text" dir="auto" name="last-name" id="last-name" value="<?php echo isset($lastName) ? $lastName : '' ?>" maxlength="128" required />
			</div>
			<div class="submit-btn">
				<button type="submit">Save changes</button>
			</div>
		</form>
	</div>
</div>