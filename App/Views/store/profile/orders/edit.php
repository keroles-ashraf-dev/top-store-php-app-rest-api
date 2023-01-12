<div class="center-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li><a href="<?php echo url('/profile/addresses'); ?>" data-translationKey="yourAddresses">Your Addresses</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="editAddress">Edit Address</li>
		</ol>
	</div>
	<h2 data-translationKey="editAddress">Edit Address</h2>
	<div class="form-box">
		<div id="js-response-container" class="response-container"></div>
		<form id="js-addresses-edit-form" action="<?php echo url('/profile/addresses/edit/save'); ?>" method="POST">
			<input type="hidden" name="id" id="id" value="<?php echo $address->id ?>" />
			<div class="country-box">
				<label for="country">Country</label>
				<span id="js-address-country"><?php echo $address->country ?></span>
				<?php readfile(url("App/Views/common/countries.php")); ?>
			</div>
			<div class="state-box">
				<label for="state">State</label>
				<input type="text" dir="auto" name="state" id="state" value="<?php echo $address->state ?>" maxlength="60" required />
			</div>
			<div class="city-box">
				<label for="city">City</label>
				<input type="text" dir="auto" name="city" id="city" value="<?php echo $address->city ?>" maxlength="60" required />
			</div>
			<div class="area-box">
				<label for="area">Area</label>
				<input type="text" dir="auto" name="area" id="area" value="<?php echo $address->area ?>" maxlength="60" required />
			</div>
			<div class="street-box">
				<label for="street">Street</label>
				<input type="text" dir="auto" name="street" id="street" value="<?php echo $address->street ?>" maxlength="60" required />
			</div>
			<div class="building-box">
				<label for="building">Building</label>
				<input type="text" dir="auto" name="building" id="building" value="<?php echo $address->building ?>" maxlength="60" required />
			</div>
			<div class="floor-box">
				<label for="floor">Floor</label>
				<input type="number" dir="auto" name="floor" id="floor" value="<?php echo $address->floor ?>" maxlength="3" required />
			</div>
			<div class="postcode-box">
				<label for="postcode">Postcode</label>
				<input type="text" dir="auto" name="postcode" id="postcode" value="<?php echo $address->postcode ?>" maxlength="24" required />
			</div>
			<div class="nearest-landmark-box">
				<label for="nearest-landmark">Nearest landmark</label>
				<input type="text" dir="auto" name="nearest-landmark" id="nearest-landmark" value="<?php echo $address->nearest_landmark ?>" maxlength="160" required />
			</div>
			<div class="submit-btn">
				<button type="submit">Edit address</button>
			</div>
		</form>
	</div>
</div>