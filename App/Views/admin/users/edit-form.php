<div id="js-edit-user-model" class="edit-user">
	<div class="header">
		<span data-translationKey="edit">Edit</span>
		<span><?php echo ucwords($user->first_name . ' ' . $user->last_name) ?></span>
	</div>
	<div class="response-container" id="js-edit-form-response-container"></div>
	<div class="form-box">
		<form id="js-edit-user-form" action="<?php echo url('admin/users/save/' . $user->id); ?>" method="POST">
			<input type="hidden" name="id" id="id" value="<?php echo $user->id ?>" />
			<div class="name-box">
				<div class="first-name">
					<label for="first-name">First name</label>
					<input type="text" dir="auto" name="first-name" id="first-name" value="<?php echo $user->first_name ?>" maxlength="128" required />
				</div>
				<div class="last-name">
					<label for="last-name">Last name</label>
					<input type="text" dir="auto" name="last-name" id="last-name" value="<?php echo $user->last_name ?>" maxlength="128" required />
				</div>
			</div>
			<div class="communication-box">
				<div class="email">
					<label for="email">Email</label>
					<input type="email" dir="ltr" name="email" id="email" value="<?php echo $user->email ?>" maxlength="128" required />
				</div>
				<div class="phone">
					<label for="phone">Phone number</label>
					<input type="tel" dir="ltr" name="phone" id="phone" value="<?php echo $user->phone ?>" maxlength="128" required>
				</div>
			</div>
			<div class="role-status-box">
				<div class="role">
					<label for="role">User role</label>
					<select name="role" id="role">
						<option <?php echo $user->role == 'admin' ? 'selected' : '' ?> value="admin">Admin</option>
						<option <?php echo $user->role == 'member' ? 'selected' : '' ?> value="member">Member</option>
					</select>
				</div>
				<div class="status">
					<label for="status">Status</label>
					<select name="status" id="status">
						<option <?php echo $user->status == 1 ? 'selected' : '' ?> value="1">Enabled</option>
						<option <?php echo $user->status == 0 ? 'selected' : '' ?> value="0">Disabled</option>
					</select>
				</div>
			</div>
			<div class="password-box">
				<div class="password">
					<label for="password">Password</label>
					<input type="password" dir="auto" name="password" id="password" maxlength="128" minlength="8" placeholder="At least 8 characters" />
				</div>
				<div class="confirm-password">
					<label for="confirm-password">Confirm password</label>
					<input type="password" dir="auto" name="confirm-password" id="confirm-password" maxlength="128" minlength="8" />
				</div>
			</div>
			<div class="actions-box">
				<button id="js-edit-form-submit" type="submit">Edit</button>
				<button id="js-edit-form-cancel">Cancel</button>
			</div>
		</form>
	</div>