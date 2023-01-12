<div class="edit-language">
	<div class="header">
		<span data-translationKey="editLanguage">Edit language</span>
	</div>
	<div class="response-container" id="js-edit-form-response-container"></div>
	<div class="form-box">
		<form id="js-edit-form" action="<?php echo url('admin/languages/edit/save/' . $language->id); ?>" method="Post" enctype="multipart/form-data">
			<input type="hidden" name="id" id="id" value="<?php echo $language->id ?>" />
			<div class="name-code-box">
				<div class="name">
					<label for="name">Name</label>
					<input type="text" dir="auto" name="name" id="name" value="<?php echo $language->name ?>" maxlength="64" required />
				</div>
				<div class="code">
					<label for="code">Code</label>
					<input type="text" dir="auto" name="code" id="code" value="<?php echo $language->code ?>" maxlength="6" required />
				</div>
			</div>
			<div class="status-file-box">
				<div class="status">
					<label for="status">Status</label>
					<select name="status" id="status">
						<option <?php echo $language->status == 1 ? 'selected' : '' ?> value="1">Enabled</option>
						<option <?php echo $language->status == 0 ? 'selected' : '' ?> value="0">Disabled</option>
					</select>
				</div>
				<div class="file">
					<label for="file">File</label>
					<input id="js-edit-form-file-input" type="file" name="language-file" />
				</div>
			</div>
			<div class="download-box">
				<button id="js-form-json-download" data-url="<?php echo $downloadUrl ?>">
					<i class="fa-solid fa-file-arrow-down"></i>
				</button>
			</div>
			<div class="actions-box">
				<button id="js-edit-form-submit" type="submit">Edit</button>
				<button id="js-edit-form-cancel">Cancel</button>
			</div>
		</form>
	</div>