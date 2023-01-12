<div class="orders profile-sub-page center-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="ordersManagement">Orders Management</li>
		</ol>
	</div>
	<div id="js-response-container" class="response-container"></div>
	<div class="content padding-container">
		<?php if (empty($orders)) : ?>
			<h4>No orders yet</h4>
		<?php else : ?>
			<h4 class="title" data-translationKey="yourOrders">Your Orders</h4>
			<?php

			foreach ($orders as $order) {

				$html = '
				<div id="'. $order['id'] .'" class="card">
				<div class="order-data">
				<div class="data">
				<div class="col">
				<span>Order id</span>
				<span>'. $order['id'] .'</span>
				</div>
				<div class="col">
				<span>Order placed</span>
				<span>'. $order['created'] .'</span>
				</div>
				<div class="col">
				<span>Total</span>
				<span>'. $order['total'] .'$</span>
				</div>
				<div class="col">
				<span>Status</span>
				<span class="js-order-status">'. $order['status'] .'</span>
				</div>
				<div class="col">
				<span>Payment type</span>
				<span>'. $order['payment_type'] .'</span>
				</div>
				</div>
				';

				$order['status'] == 'processing'
				? $html .= '<div class="actions"><button class="js-cancel-btn" data-id="'. $order['id'] .'" data-url="' . url('/profile/orders/cancel') . '">Cancel</button></div></div>'
				: $html .= '</div>';
				
				foreach ($order['items'] as $item) {

					$html .= '
						<div class="item">
						<a href="' . url('product?id=') . $item['id'] . '">
						<div class="img-container">
						<img src="' . assets('common/images/' . $item['image']) . '" alt="">
						</div>
						</a>
						<div class="item-data">
						<span class="name">' . $item['name'] . '</span>
						<span class="price"><strong>' . $item['price'] . '$</strong></span>
						<span>Qty: <span>' . $item['count'] . '</span></span>
						</div>
						</div>
						';
				}

				$html .= '</div>';

				echo $html;
			}
			?>
		<?php endif; ?>
	</div>
</div>