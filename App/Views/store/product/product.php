<div class="product-page">
	<div class="breadcrumb-container">
		<ol>
			<?php
			$html = '
			<li><a href="?url" >?name</a></li>
			<i class="fa-solid fa-chevron-right"></i>
		';
			foreach ($categories as $category) {
				$values = [
					'name' => $category->name,
					'url' => url('/category?id=') . $category->id,
				];

				echo inject_html($html, $values);
			}
			?>
			<li><?php echo $product->name ?></li>
		</ol>
	</div>
	<div id="js-response-container" class="response-container"></div>
	<div class="images-container">
		<ol>
			<?php
			$html = '
			<li><img class="js-product-thumbnail" src="?name" alt=""></li>
			';
			foreach ($images as $image) {
				$values = [
					'name' => assets('common/images/' . $image->name),
				];
				echo inject_html($html, $values);
			}
			?>
		</ol>
		<div><img id="js-product-expanded-img" src="<?php echo assets('common/images/' . $images[0]->name) ?>" alt=""></div>
	</div>
	<div class="data-container">
		<h3 class="name"><?php echo $product->name ?></h3>
		<div class="rating-container">
			<span class="js-rating-container" data-rating="<?php echo $product->rating ?>"></span>
			<span><?php echo $product->rating ?></span>
		</div>
		<span class="price"><?php echo $product->price ?> <strong>$</strong></span>
		<span class="stock"><?php echo $product->available_count ?> <span>in stock</span></span>
		<button id="js-add-to-cart-btn" data-url="<?php echo url('/product/add-to-cart') ?>" data-id="<?php echo $product->id ?>" data-translationKey="addToCart">Add to cart</button>
		<span class="desc"><?php echo $product->description ?></span>
	</div>
	<?php if (!empty($relatedProducts)) : ?>
		<div class="related-container margin-container">
			<span class="title" data-translationKey="relatedProducts">Related Products</span>
			<div class="products-row">
				<?php
				$html = '
        <div class="product-card">
        <a href="?overviewUrl">
        <div class="img-container">
        <img src="?image" alt="">
        </div>
        <span class="name">?name</span>
        <span class="price">?price$</span>
        <span class="stock">?available in stock</span>
        <div class="rating-container">
            <span class="js-rating-container" data-rating="?rating"></span>
            <span>?ratersCount</span>
        </div>
        </a>
        </div>';
				foreach ($relatedProducts as $product) {
					$values = [
						'name' => $product->name,
						'image' => assets('common/images/' . $product->image),
						'price' => $product->price,
						'available' => $product->available_count,
						'rating' => $product->rating,
						'ratersCount' => $product->raters_count,
						'overviewUrl' => url('product?id=') . $product->id,
					];
					echo inject_html($html, $values);
				}
				?>
			</div>
		</div>
	<?php endif; ?>
</div>