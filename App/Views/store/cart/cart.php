<div class="cart-page">
	<div id="js-response-container" class="response-container"></div>
	<div class="products-container padding-container">
		<?php if (empty($products)) : ?>
			<h4>Your cart is empty</h4>
		<?php else : ?>
			<span class="title" data-translationKey="shoppingCart">Shopping Cart</span>
			<div class="products-col">
				<span class="line"></span>
				<?php
				$html = '
        <div id="?id" class="product-card">
        <a href="?overviewUrl">
        <div class="img-container">
        <img src="?image" alt="">
        </div>
        </a>
				<div class="data">
				<span class="name">?name</span>
        <span class="available">?availableCount In Stock</span>
        <span class="price"><s>?price$</s></span>
        <span class="discounted">?discountedPrice$</span>
        <div class="count-container">
				<span>Qty: <span id="js-product-count">?count</span></span>
				<i class="js-cart-increment fa-solid fa-plus" data-id="?id" data-url="?incrementUrl"></i>
				<i class="js-cart-decrement fa-solid fa-minus" data-id="?id" data-url="?decrementUrl"></i>
				</div>
				</div>
        </div>
				<span class="line"></span>
				';
				foreach ($products as $product) {
					$values = [
						'id' => $product->id,
						'name' => $product->name,
						'image' => assets('common/images/' . $product->image),
						'price' => $product->price,
						'discountedPrice' => empty($product->discounted_price) ? $product->price : $product->discounted_price,
						'count' => $product->count,
						'availableCount' => $product->available_count,
						'overviewUrl' => url('product?id=') . $product->id,
						'incrementUrl' => url('/product/increment'),
						'decrementUrl' => url('/product/decrement'),
					];
					echo inject_html($html, $values);
				}
				?>
				<div class="subtotal">
					<span>Subtotal: </span>
					<strong><span id="js-cart-subtotal"><?php echo $subtotalPrice ?></span>$</strong>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="vertical-line-container"></div>
	<div class="checkout-container">
		<h3>Order Summary</h3>
		<div class="row items">
			<span>Items:</span>
			<span><strong><span id="js-cart-items"><?php echo $subtotalPrice ?></span>$</strong></span>
		</div>
		<div class="row shipping">
			<span>Shipping:</span>
			<span><strong><span><?php echo $shipping ?></span>$</strong></span>
		</div>
		<div class="row vat">
			<span>VAT:</span>
			<span><strong><span id="js-cart-vat" data-vat="<?php echo $vatPercent ?>"><?php echo $vat ?></span>$</strong></span>
		</div>
		<div class="row total">
			<span>Total:</span>
			<span><strong><span id="js-cart-total"><?php echo ($vat + $shipping + $subtotalPrice) ?></span>$</strong></span>
		</div>
		<div class="row">
			<span>Payment:</span>
		<div>
			<input id="js-payment-cash" type="radio" name="payment" checked value="cash"> Cash
			<input id="js-payment-digital" type="radio" name="payment" value="digital"> Digital
		</div>
		</div>
		<button id="js-checkout-btn" data-url="<?php echo url('profile/orders/create') ?>" data-translationKey="checkout">Checkout</button>
	</div>
</div>