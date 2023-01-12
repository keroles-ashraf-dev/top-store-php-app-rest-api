<div class="addresses center-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="yourAddresses">Your Addresses</li>
		</ol>
	</div>
	<h2 data-translationKey="yourAddresses">Your Addresses</h2>
	<span class="hint">Click on address card to select your default address</span>
	<div class="content">
		<div class="card add-card">
			<a href="<?php echo url('/profile/addresses/add') ?>">
				<i class="fa-solid fa-plus"></i>
				<h3 data-translationKey="addAddress">Add Address</h3>
			</a>
		</div>
		<?php
		if (!empty($addresses)) {

			foreach ($addresses as $address) {

				$html = '
				<div id="?id" class="card js-address-card" data-id="?id" data-url="?setDefaultAddress">
				<div class="address">'
					. ($address->id == $default ? '<div class="hint default">default</div>' : '') .
					'<span>?nearestLandmark</span>
						<span>?floor, ?building, ?street</span>
						<span>?area, ?city, ?state</span>
						<span>?country</span>
						<span>?postcode</span>
					</div>
					<div class="actions">
					<a href="?editUrl"><button class="js-edit-btn" data-id="?id" data-url="?editUrl">Edit</button></a>
						<span>|</span>
						<button class="js-remove-btn" data-id="?id" data-url="?removeUrl">Remove</button>
					</div>
				</div>
				';

				$values = [
					'id' => $address->id,
					'nearestLandmark' => $address->nearest_landmark,
					'floor' => $address->floor,
					'building' => $address->building,
					'street' => $address->street,
					'area' => $address->area,
					'city' => $address->city,
					'state' => $address->state,
					'country' => $address->country,
					'postcode' => $address->postcode,
					'editUrl' => url('profile/addresses/edit?id=') . $address->id,
					'removeUrl' => url('profile/addresses/remove'),
					'setDefaultAddress' => url('profile/addresses/set-default'),
				];
				echo inject_html($html, $values);
			}
		}
		?>
	</div>
</div>