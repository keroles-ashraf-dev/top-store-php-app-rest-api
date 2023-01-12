<div id="js-edit-product-model" class="edit-product">
	<div class="header">
		<span data-translationKey="edit">Edit</span>
		<span><?php echo ucwords($product->name) ?></span>
	</div>
	<div class="response-container" id="js-edit-form-response-container"></div>
	<div class="form-box">
		<form id="js-edit-product-form" action="<?php echo url('admin/products/edit/save/' . $product->id); ?>" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="id" id="id" value="<?php echo $product->id ?>" />
			<div class="name-box">
				<label for="name">Name</label>
				<input type="text" dir="auto" name="name" id="name" value="<?php echo $product->name ?>" maxlength="55" required />
			</div>
			<div class="description-box">
				<label for="description">Description</label>
				<textarea id="description" name="description" rows="4" dir="auto" maxlength="500" required><?php echo $product->description ?></textarea>
			</div>
			<div class="price-available-box">
				<div class="price">
					<label for="price">Price</label>
					<input type="number" dir="auto" name="price" id="price" value="<?php echo $product->price ?>" step="0.01" maxlength="11" required />
				</div>
				<div class="available-count">
					<label for="available-count">Available count</label>
					<input type="number" dir="auto" name="available-count" id="available-count" value="<?php echo $product->available_count ?>" maxlength="6" required />
				</div>
			</div>
			<div class="category-status-box">
				<div class="category-id">
					<label for="category-id">Category</label>
					<select name="category-id" id="category-id">
						<?php
						foreach ($categories as $category) {
							$html = '<option ' . ($product->category_id == $category->id ? 'selected' : '') . ' value="?categoryId">?categoryName</option>';
							$values = [
								'categoryId' => $category->id,
								'categoryName' => $category->name,
							];
							echo inject_html($html, $values);
						}
						?>
					</select>
				</div>
				<div class="status">
					<label for="status">Status</label>
					<select name="status" id="status">
						<option <?php echo $product->status == 1 ? 'selected' : '' ?> value="1">Enabled</option>
						<option <?php echo $product->status == 0 ? 'selected' : '' ?> value="0">Disabled</option>
					</select>
				</div>
			</div>
			<div class="images-box">
				<label for="images">Images</label>
				<input id="js-edit-product-form-image-input" type="file" name="product-images" multiple />
			</div>
			<div id="js-images-container" class="image-container">
				<?php
				$html = '<img id="js-edit-product-form-image" src="?imageSrc" alt="" />';
				foreach ($images as $image) {
					$values = [
						'imageSrc' => assets('common/images/' . $image->name),
					];
					echo inject_html($html, $values);
				}
				?>
			</div>
			<div class="actions-box">
				<button id="js-edit-form-submit" type="submit">Edit</button>
				<button id="js-edit-form-cancel">Cancel</button>
			</div>
		</form>
	</div>