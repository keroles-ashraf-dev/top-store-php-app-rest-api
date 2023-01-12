<footer>
	<button id="js-back-to-top-btn" class="back-to-top" data-translationKey="backToTop">Back to top</button>
	<div class="trade-mark">
		<span data-translationKey="footerTradeMark">©1996–<?php echo date("Y") ?>, Store.com, Inc. or its affiliates</span>
	</div>
</footer>
<script type="module" src="<?php echo assets('common/js/storage.js') ?>"></script>
<script type="module" src="<?php echo assets('common/js/helpers.js') ?>"></script>
<script type="module" src="<?php echo assets('common/js/i18n.js') ?>"></script>
<script type="module" src="<?php echo assets('admin/js/navbar.js') ?>"></script>
<script type="module" src="<?php echo assets('admin/js/footer.js') ?>"></script>

<?php
// just import current page js file only
$html = '<script type="module" src="?path"></script>';

if (str_starts_with($path, '/admin/users')) {
	echo inject_html($html, ['path' => assets('admin/js/users.js')]);
} else if (str_starts_with($path, '/admin/categories')) {
	echo inject_html($html, ['path' => assets('admin/js/categories.js')]);
} else if (str_starts_with($path, '/admin/products')) {
	echo inject_html($html, ['path' => assets('admin/js/products.js')]);
} else if (str_starts_with($path, '/admin/offers') || $path == '/admin') {
	echo inject_html($html, ['path' => assets('admin/js/offers.js')]);
} else if (str_starts_with($path, '/admin/languages')) {
	echo inject_html($html, ['path' => assets('admin/js/languages.js')]);
}else if (str_starts_with($path, '/admin/settings')) {
	echo inject_html($html, ['path' => assets('admin/js/settings.js')]);
}else if (str_starts_with($path, '/admin/support/chat')) {
	echo inject_html($html, ['path' => assets('admin/js/chat.js')]);
}



?>

</body>

</html>