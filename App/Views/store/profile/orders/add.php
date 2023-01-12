<div class="center-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li><a href="<?php echo url('/profile/addresses'); ?>" data-translationKey="yourAddresses">Your Addresses</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="addAddress">Add Address</li>
		</ol>
	</div>
	<h2 data-translationKey="addAddress">Add Address</h2>
	<div class="form-box">
		<div id="js-response-container" class="response-container"></div>
		<form id="js-addresses-add-form" action="<?php echo url('/profile/addresses/add/save'); ?>" method="POST">
			<div class="country-box">
				<label for="country">Country</label>
				<?php readfile(url("App/Views/common/countries.php")); ?>
			</div>
			<div class="state-box">
				<label for="state">State</label>
				<input type="text" dir="auto" name="state" id="state" autocomplete="on" maxlength="60" required />
			</div>
			<div class="city-box">
				<label for="city">City</label>
				<input type="text" dir="auto" name="city" id="city" maxlength="60" required />
			</div>
			<div class="area-box">
				<label for="area">Area</label>
				<input type="text" dir="auto" name="area" id="area" maxlength="60" required />
			</div>
			<div class="street-box">
				<label for="street">Street</label>
				<input type="text" dir="auto" name="street" id="street" maxlength="60" required />
			</div>
			<div class="building-box">
				<label for="building">Building</label>
				<input type="text" dir="auto" name="building" id="building" maxlength="60" required />
			</div>
			<div class="floor-box">
				<label for="floor">Floor</label>
				<input type="number" dir="auto" name="floor" id="floor" maxlength="3" required />
			</div>
			<div class="postcode-box">
				<label for="postcode">Postcode</label>
				<input type="text" dir="auto" name="postcode" id="postcode" maxlength="24" required />
			</div>
			<div class="nearest-landmark-box">
				<label for="nearest-landmark">Nearest landmark</label>
				<input type="text" dir="auto" name="nearest-landmark" id="nearest-landmark" maxlength="160" required />
			</div>
			<div class="submit-btn">
				<button type="submit">Add address</button>
			</div>
		</form>
	</div>
</div>