<div id="js-add-slider-model" class="add-slider">
	<div class="header">
		<span data-translationKey="addNewProduct">Add new slider item</span>
	</div>
	<div class="response-container" id="js-add-slider-form-response-container"></div>
	<div class="form-box">
		<form id="js-add-form" action="<?php echo url('admin/offers/add-slider/save'); ?>" method="Post" enctype="multipart/form-data">
			<div class="images-box">
				<label for="images">Images</label>
				<input id="js-add-form-image-input" type="file" name="slider-image" required />
			</div>
			<div id="js-add-image-container" class="image-container"></div>
			<div class="actions-box">
				<button id="js-add-form-submit" type="submit">Add</button>
				<button id="js-add-form-cancel">Cancel</button>
			</div>
		</form>
	</div>
</div>