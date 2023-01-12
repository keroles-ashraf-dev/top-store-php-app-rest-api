<div id="js-add-category-model" class="add-category">
	<div class="header">
		<span data-translationKey="addNewCategory">Add new category</span>
	</div>
	<div class="response-container" id="js-add-form-response-container"></div>
	<div class="form-box">
		<form id="js-add-category-form" action="<?php echo url('admin/categories/add/save'); ?>" method="Post" enctype="multipart/form-data">
			<div class="parent-id-name-box">
				<div class="parent-id">
					<label for="parent-id">Parent id</label>
					<input type="text" dir="auto" name="parent-id" id="parent-id" maxlength="11" />
				</div>
				<div class="name">
					<label for="name">Name</label>
					<input type="text" dir="auto" name="name" id="name" maxlength="128" required />
				</div>
			</div>
			<div class="status-image-box">
				<div class="status">
					<label for="status">Status</label>
					<select name="status" id="status">
						<option selected value="1">Enabled</option>
						<option value="0">Disabled</option>
					</select>
				</div>
				<div class="image">
					<label for="image">Image</label>
					<input id="js-add-category-form-image-input" type="file" name="image" id="image">
				</div>
			</div>
			<div class="image-container">
				<img id="js-add-category-form-image" src="" alt="" />
			</div>
			<div class="actions-box">
				<button id="js-add-form-submit" type="submit">Add</button>
				<button id="js-add-form-cancel">Cancel</button>
			</div>
		</form>
	</div>