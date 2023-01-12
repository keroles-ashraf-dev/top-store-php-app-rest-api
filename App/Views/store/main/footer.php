<footer>
	<button id="js-back-to-top-btn" class="back-to-top" data-translationKey="backToTop">Back to top</button>
	<div class="about">
		<ul>
			<li data-translationKey="getToKnowUs">Get to Know Us</li>
			<li><a href="" data-translationKey="aboutStore">About Store</a></li>
			<li><a href="" data-translationKey="careers">Careers</a></li>
			<li><a href="" data-translationKey="Science">Store Science</a></li>
		</ul>
		<ul>
			<li data-translationKey="shopWithUs">Shop with Us</li>
			<li><a href="" data-translationKey="yourAccount">Your Account</a></li>
			<li><a href="" data-translationKey="yourOrders">Your Orders</a></li>
			<li><a href="" data-translationKey="yourAddresses">Your Addresses</a></li>
		</ul>
		<ul>
			<li data-translationKey="makeMoneyWithUs">Make Money with Us</li>
			<li><a href="" data-translationKey="advertiseYourProducts">Advertise Your Products</a></li>
			<li><a href="" data-translationKey="sellOnStore">Sell on Store</a></li>
			<li><a href="" data-translationKey="fulFillmentByStore">Fulfillment by Store</a></li>
		</ul>
		<ul>
			<li data-translationKey="letUsHelpYou">Let Us Help You</li>
			<li><a href="" data-translationKey="help">Help</a></li>
			<li><a href="" data-translationKey="shipping&Delivery">Shipping & Delivery</a></li>
			<li><a href="" data-translationKey="returns&Replacement">Returns & Replacements</a></li>
		</ul>
	</div>
	<div class="privacy">
		<ul>
			<li>
				<a href="" data-translationKey="conditionsOfUse&Sale">Conditions of Use & Sale</a>
			</li>
			<li>
				<a href="" data-translationKey="privacyNotice">Privacy Notice</a>
			</li>
			<li>
				<a href="" data-translationKey="interest-BasedAds">Interest-Based Ads</a>
			</li>
		</ul>
		<span data-translationKey="footerTradeMark">©1996–<?php echo date("Y") ?>, Store.com, Inc. or its affiliates</span>
	</div>
</footer>
<script type="module" src="<?php echo assets('common/js/storage.js') ?>"></script>
<script type="module" src="<?php echo assets('common/js/helpers.js') ?>"></script>
<script type="module" src="<?php echo assets('common/js/i18n.js') ?>"></script>
<script type="module" src="<?php echo assets('store/js/navbar.js') ?>"></script>
<script type="module" src="<?php echo assets('store/js/footer.js') ?>"></script>

<?php
// just import current page js file only
$html = '<script type="module" src="?path"></script>';

if ($path === '/' || str_starts_with($path, '/home')) {
	echo inject_html($html, ['path' => assets('store/js/home.js')]);
} else if (str_starts_with($path, '/login')) {
	echo inject_html($html, ['path' => assets('common/js/login.js')]);
} else if (str_starts_with($path, '/register')) {
	echo inject_html($html, ['path' => assets('common/js/register.js')]);
} else if (str_starts_with($path, '/email-verifying')) {
	echo inject_html($html, ['path' => assets('common/js/email-verifying.js')]);
} else if (str_starts_with($path, '/profile/data')) {
	echo inject_html($html, ['path' => assets('store/js/profile-data.js')]);
} else if (str_starts_with($path, '/profile/addresses')) {
	echo inject_html($html, ['path' => assets('store/js/addresses.js')]);
} else if (str_starts_with($path, '/profile/orders')) {
	echo inject_html($html, ['path' => assets('store/js/orders.js')]);
} else if (str_starts_with($path, '/product')) {
	echo inject_html($html, ['path' => assets('store/js/product.js')]);
}else if (str_starts_with($path, '/cart')) {
	echo inject_html($html, ['path' => assets('store/js/cart.js')]);
}else if (str_starts_with($path, '/category')) {
	echo inject_html($html, ['path' => assets('store/js/category.js')]);
}else if (str_starts_with($path, '/support/chat')) {
	echo inject_html($html, ['path' => assets('store/js/chat.js')]);
}else if (str_starts_with($path, '/search')) {
	echo inject_html($html, ['path' => assets('store/js/search.js')]);
}
?>

</body>

</html>