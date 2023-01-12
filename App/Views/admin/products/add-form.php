<div id="js-add-product-model" class="add-product">
	<div class="header">
		<span data-translationKey="addNewProduct">Add new product</span>
	</div>
	<div class="response-container" id="js-add-form-response-container"></div>
	<div class="form-box">
		<form id="js-add-product-form" action="<?php echo url('admin/products/add/save'); ?>" method="Post" enctype="multipart/form-data">
			<div class="name-box">
				<label for="name">Name</label>
				<input type="text" dir="auto" name="name" id="name" maxlength="55" required />
			</div>
			<div class="description-box">
				<label for="description">Description</label>
				<textarea id="description" name="description" rows="4" dir="auto" maxlength="500" required></textarea>
			</div>
			<div class="price-available-box">
				<div class="price">
					<label for="price">Price</label>
					<input type="number" dir="auto" name="price" id="price" step="0.01" maxlength="11" required />
				</div>
				<div class="available-count">
					<label for="available-count">Available count</label>
					<input type="number" dir="auto" name="available-count" id="available-count" maxlength="6" required />
				</div>
			</div>
			<div class="category-status-box">
				<div class="category-id">
					<label for="category-id">Category</label>
					<select name="category-id" id="category-id">
						<?php
						$html = '<option value="?categoryId">?categoryName</option>';
						foreach ($categories as $category) {
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
						<option selected value="1">Enabled</option>
						<option value="0">Disabled</option>
					</select>
				</div>
			</div>
			<div class="images-box">
				<label for="images">Images</label>
				<input id="js-add-product-form-image-input" type="file" name="product-images" multiple required />
			</div>
			<div id="js-images-container" class="image-container"></div>
			<div class="actions-box">
				<button id="js-add-form-submit" type="submit">Add</button>
				<button id="js-add-form-cancel">Cancel</button>
			</div>
		</form>
	</div>