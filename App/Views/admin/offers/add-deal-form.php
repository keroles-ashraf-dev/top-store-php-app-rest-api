<div id="js-add-deals-model" class="add-deals">
	<div class="header">
		<span data-translationKey="addNewProduct">Add new deals item</span>
	</div>
	<div class="response-container" id="js-add-form-response-container"></div>
	<div class="form-box">
		<form id="js-add-form" action="<?php echo url('admin/offers/add-deal/save'); ?>" method="Post">
			<div class="product-id-price-box">
				<div class="product-id">
					<label for="product-id">Product id</label>
					<input type="text" dir="auto" name="product-id" id="product-id" maxlength="11" required />
				</div>
				<div class="price">
					<label for="price">Price</label>
					<input type="number" dir="auto" name="price" id="price" step="0.01" required />
				</div>
			</div>
			<div class="status-box">
				<label for="status">Status</label>
				<select name="status" id="status">
					<option selected value="1">Enabled</option>
					<option value="0">Disabled</option>
				</select>
			</div>
			<div class="actions-box">
				<button id="js-add-form-submit" type="submit">Add</button>
				<button id="js-add-form-cancel">Cancel</button>
			</div>
		</form>
	</div>
</div>