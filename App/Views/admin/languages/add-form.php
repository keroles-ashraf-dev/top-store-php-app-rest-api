<div class="add-language">
	<div class="header">
		<span data-translationKey="addNewLanguage">Add new language</span>
	</div>
	<div class="response-container" id="js-add-form-response-container"></div>
	<div class="form-box">
		<form id="js-add-form" action="<?php echo url('admin/languages/add/save'); ?>" method="Post" enctype="multipart/form-data">
			<div class="name-code-box">
				<div class="name">
					<label for="name">Name</label>
					<input type="text" dir="auto" name="name" id="name" maxlength="64" required />
				</div>
				<div class="code">
					<label for="code">Code</label>
					<input type="text" dir="auto" name="code" id="code" maxlength="6" required />
				</div>
			</div>
			<div class="status-file-box">
				<div class="status">
					<label for="status">Status</label>
					<select name="status" id="status">
						<option selected value="1">Enabled</option>
						<option value="0">Disabled</option>
					</select>
				</div>
				<div class="file">
					<label for="file">File</label>
					<input id="js-add-form-file-input" type="file" name="language-file" required />
				</div>
			</div>
			<div class="actions-box">
				<button id="js-add-form-submit" type="submit">Add</button>
				<button id="js-add-form-cancel">Cancel</button>
			</div>
		</form>
	</div>