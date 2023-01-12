<div class="languages-page">
  <h1>Languages Management</h1>
  <div class="add-language-button-container">
    <Button id="js-add" data-url="<?php echo url('/admin/languages/add'); ?>">Add language</Button>
  </div>
  <div class="response-container" id="js-response-container"></div>
  <?php if (empty($languages)) : ?>
    <div class="empty">No Languages Found</div>
  <?php else : ?>
    <table>
      <tr>
        <th data-translationKey="id">Id</th>
        <th data-translationKey="name">Name</th>
        <th data-translationKey="code">Code</th>
        <th data-translationKey="file">File</th>
        <th data-translationKey="status">Status</th>
        <th data-translationKey="actions">Actions</th>
      </tr>
      <?php
      foreach ($languages as $language) {
        $html = '
          <tr id="?id">
          <td>?id</td>
          <td>?name</td>
          <td>?code</td>
          <td>?file</td>
          <td id="js-status-field">?status</td>
          <td>
          <button class="js-edit" data-id="?id" data-url="?editUrl"><i class="fa-solid fa-edit"></i></button>
          <button class="js-status" data-id="?id" data-url="?changeStatusUrl"><i class="fa-solid ?changeStatusActionIcon"></i></button>
          <button class="js-delete" data-id="?id" data-url="?deleteUrl"><i class="fa-solid fa-trash"></i></button>
          </td>
          </tr>';
        $values = [
          'id' => $language->id,
          'name' => $language->name,
          'code' => $language->code,
          'file' => $language->file,
          'status' => $language->status == 1 ? 'enabled' : 'disabled',
          'editUrl' => url('admin/languages/edit') . '/' . $language->id,
          'changeStatusUrl' => url('admin/languages/change-status'),
          'changeStatusActionIcon' => $language->status == 1 ? 'fa-ban' : 'fa-circle-check',
          'deleteUrl' => url('admin/languages/delete'),
        ];
        echo inject_html($html, $values);
      }
      ?>
    </table>
  <?php endif; ?>
  <div id="js-edit-model-container" class="model-container"></div>
  <div id="js-add-model-container" class="model-container"></div>
</div>