<div class="settings-page">
	<h1>Settings Management</h1>
	<div class="response-container" id="js-response-container"></div>
	<div class="form-box">
		<form id="js-settings-form" action="<?php echo url('admin/settings/save'); ?>" method="POST">
		<div class="site-nav-announcement-box">
				<label for="nav-announcement">Nav announcement</label>
				<input type="text" dir="auto" name="nav-announcement" id="nav-announcement" value="<?php echo isset($settings['nav_announcement']) ? $settings['nav_announcement'] : '' ?>" maxlength="200" required />
			</div>
			<div class="site-name-box">
				<label for="site-name">Site name</label>
				<input type="text" dir="auto" name="site-name" id="site-name" value="<?php echo isset($settings['site_name']) ? $settings['site_name'] : '' ?>" maxlength="200" required />
			</div>
			<div class="site-email-box">
				<label for="site-email">Site email</label>
				<input type="email" dir="auto" name="site-email" id="site-email" value="<?php echo isset($settings['site_email']) ? $settings['site_email'] : '' ?>" maxlength="200" required />
			</div>
			<div class="site-close-msg-box">
				<label for="site-close-msg">Site close message</label>
				<textarea id="site-close-msg" name="site-close-msg" rows="4" dir="auto" maxlength="200" required><?php echo isset($settings['site_close_msg']) ? $settings['site_close_msg'] : '' ?></textarea>
			</div>
			<div class="site-status-box">
				<label for="site-status">Site status</label>
				<select name="site-status" id="site-status">
					<?php
					if (!isset($settings['site_status'])) $settings['site_status'] = 1; ?>
					<option <?php echo  $settings['site_status'] == 1 ? 'selected' : '' ?> value="1">Enabled</option>
					<option <?php echo $settings['site_status'] == 0 ? 'selected' : '' ?> value="0">Disabled</option>
				</select>
			</div>
			<div class="action-box">
				<button id="js-form-submit" type="submit">Save</button>
			</div>
		</form>
	</div>
</div>