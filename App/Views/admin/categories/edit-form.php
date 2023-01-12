<div id="js-edit-category-model" class="edit-category">
	<div class="header">
		<span data-translationKey="edit">Edit</span>
		<span><?php echo ucwords($category->name) ?></span>
	</div>
	<div class="response-container" id="js-edit-form-response-container"></div>
	<div class="form-box">
		<form id="js-edit-category-form" action="<?php echo url('admin/categories/edit/save/' . $category->id); ?>" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="id" id="id" value="<?php echo $category->id ?>" />
			<div class="parent-id-name-box">
				<div class="parent-id">
					<label for="parent-id">Parent id</label>
					<input type="text" dir="auto" name="parent-id" id="parent-id" value="<?php echo $category->parent_id ?>" maxlength="11" />
				</div>
				<div class="name">
					<label for="name">Name</label>
					<input type="text" dir="auto" name="name" id="name" value="<?php echo $category->name ?>" maxlength="128" required />
				</div>
			</div>
			<div class="status-image-box">
				<div class="status">
					<label for="status">Status</label>
					<select name="status" id="status">
						<option <?php echo $category->status == 1 ? 'selected' : '' ?> value="1">Enabled</option>
						<option <?php echo $category->status == 0 ? 'selected' : '' ?> value="0">Disabled</option>
					</select>
				</div>
				<div class="image">
					<label for="image">Image</label>
					<input id="js-edit-category-form-image-input" type="file" name="image" id="image">
				</div>
			</div>
			<div class="image-container">
				<img id="js-edit-category-form-image" src="<?php echo assets('common/images/' . $category->image); ?>" alt="" />
			</div>
			<div class="actions-box">
				<button id="js-edit-form-submit" type="submit">Edit</button>
				<button id="js-edit-form-cancel">Cancel</button>
			</div>
		</form>
	</div>