<div class="category-page">
	<?php if (!empty($subCategories)) : ?>
		<div class="sub-categories-container">
			<?php
			if (!empty($subCategories)) {
				foreach ($subCategories as $category) {
					$html = '<a href="' . (url('category?id=') . $category->id) . '">' . ($category->name) . '</a>';
					echo $html;
				}
			}
			?>
		</div>
	<?php endif; ?>
	<div class="filtering-container">
		<form action="<?php echo url('/search') ?>" method="GET">
			<input type="hidden" name="keyword" value="<?php echo $keyword ?>">
			<input type="hidden" name="category-id" value="<?php echo $categoryId ?>">
			<span class="title">Sort by:</span>
			<div class="filters">
				<div><input type="radio" name="sort-by" <?php echo ($sortBy == 'name' ? 'checked' : '') ?> value="name"><span>Name</span></div>
				<div><input type="radio" name="sort-by" <?php echo ($sortBy == 'price' ? 'checked' : '') ?> value="price"><span>Price</span></div>
				<div><input type="radio" name="sort-by" <?php echo ($sortBy == 'rating' ? 'checked' : '') ?> value="rating"><span>Rating</span></div>
			</div>
			<span class="title">Order by:</span>
			<div class="orders">
				<div><input type="radio" name="order-by" <?php echo ($orderBy == 'ASC' ? 'checked' : '') ?> value="ASC"><span>Ascending</span></div>
				<div><input type="radio" name="order-by" <?php echo ($orderBy == 'DESC' ? 'checked' : '') ?> value="DESC"><span>Descending</span></div>
			</div>
			<div class="action"><button type="submit">Submit</button></div>
		</form>
	</div>
	<div class="products-container">
		<?php
		if (empty($products)) {
			$html = '<div class="no-results">No results</div>';
			echo $html;
		} else {
			foreach ($products as $product) {
				$html = '
					<div class="product-card">
					<a href="?overviewUrl">
					<div class="img-container">
					<img src="?image" alt="">
					</div>
					<span class="name">?name</span>
					' . (empty($product->discounted_price) ? '' : '<span class="price">?discountedPrice <strong>$</strong></span>') . '
					' . (empty($product->discounted_price) ? '<span class="price">?price$</span>' : '<span class="old-price"><s>?price $</s></span>') . '
					' . (empty($product->discounted_price) ? '' : '<span class="discount">?discountPercentage% off</span>') . '
					<div class="rating-container">
					<span class="js-rating-container" data-rating="?rating"></span>
					<span>?ratersCount</span>
					</div>
					<span class="stock">?count in stock</span>
					</a>
					</div>';

				$values = [
					'id' => $product->id,
					'name' => $product->name,
					'count' => $product->available_count,
					'image' => assets('common/images/' . $product->image),
					'discountedPrice' => $product->discounted_price,
					'price' => $product->price,
					'discountPercentage' => empty($product->discounted_price) ? '' : ceil((($product->price - $product->discounted_price) / $product->price) * 100),
					'rating' => $product->rating,
					'ratersCount' => $product->raters_count,
					'overviewUrl' => url('product?id=') . $product->id,
				];
				echo inject_html($html, $values);
			}
		}
		?>
	</div>
	<div class="pagination-container">
		<?php if ($pagination->totalItems() > $pagination->itemsPerPage()) : ?>
			<div class="pagination">
				<a href="<?php echo url('search?keyword=') . $keyword . '&category-id=' . $categoryId . '&sort-by=' . $sortBy . '&order-by=' . $orderBy . '&page=' . $pagination->prev() ?>">&laquo;</a>
				<?php
				for ($i = 1; $i <= $pagination->last(); $i++) {
					$html = '<a ' . ($i == $pagination->page() ? 'class="active"' : '') . ' href="' . url('search?keyword=') . $keyword . '&category-id=' . $categoryId . '&sort-by=' . $sortBy . '&order-by=' . $orderBy . '&page=' . $i . '">' . $i . '</a>';
					echo $html;
				}
				?>
				<a href="<?php echo url('search?keyword=') . $keyword . '&category-id=' . $categoryId . '&sort-by=' . $sortBy . '&order-by=' . $orderBy . '&page=' . $pagination->next() ?>">&raquo;</a>
			</div>
		<?php endif; ?>
	</div>
</div>