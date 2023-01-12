<div class="categories-page">
  <h1>Categories Management</h1>
  <div class="search-container">
    <form action="<?php echo url('/admin/categories/search'); ?>" method="GET">
      <input type="text" name="search-key" placeholder="Search by name, id or parent id" value="<?php echo isset($searchKey) ? $searchKey : '' ?>">
      <button type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>
  <div class="add-category-button-container">
    <Button id="js-categories-add" data-url="<?php echo url('/admin/categories/add'); ?>">Add category</Button>
  </div>
  <div class="response-container" id="js-response-container"></div>
  <?php if (empty($categories)) : ?>
    <div class="empty">No Categories Found</div>
  <?php else : ?>
    <table>
      <tr>
        <th data-translationKey="id">Id</th>
        <th data-translationKey="name">Name</th>
        <th data-translationKey="parentId">Parent id</th>
        <th data-translationKey="parentName">Parent name</th>
        <th data-translationKey="status">Status</th>
        <th data-translationKey="actions">Actions</th>
      </tr>
      <?php
      foreach ($categories as $category) {
        $html = '
          <tr id="?id">
          <td>?id</td>
          <td>?name</td>
          <td>?parentId</td>
          <td>?parentName</td>
          <td id="js-category-status-field">?status</td>
          <td>
          <button class="js-categories-edit" data-id="?id" data-url="?editUrl"><i class="fa-solid fa-edit"></i></button>
          <button class="js-categories-status" data-id="?id" data-url="?changeStatusUrl"><i class="fa-solid ?changeStatusActionIcon"></i></button>
          <button class="js-categories-delete" data-id="?id" data-url="?deleteUrl"><i class="fa-solid fa-trash"></i></button>
          </td>
          </tr>';
        $values = [
          'id' => $category->id,
          'name' => $category->name,
          'parentId' => $category->parent_id ?: '',
          'parentName' => $category->parent_name ?: '',
          'status' => $category->status == 1 ? 'enabled' : 'disabled',
          'editUrl' => url('admin/categories/edit') . '/' . $category->id,
          'changeStatusUrl' => url('admin/categories/change-status'),
          'changeStatusActionIcon' => $category->status == 1 ? 'fa-ban' : 'fa-circle-check',
          'deleteUrl' => url('admin/categories/delete'),
        ];
        echo inject_html($html, $values);
      }
      ?>
    </table>
    <?php if ($pagination->totalItems() > $pagination->itemsPerPage()) : ?>
      <div class="pagination">
        <a href="<?php echo $url . $pagination->prev() ?>">&laquo;</a>
        <?php
        for ($i = 1; $i <= $pagination->last(); $i++) {
          $html = '<a ' . ($i == $pagination->page() ? 'class="active"' : '') . ' href="' . $url . $i . '">?id</a>';
          echo inject_html($html, ['id' => $i]);
        }
        ?>
        <a href="<?php echo $url . $pagination->next() ?>">&raquo;</a>
      </div>
    <?php endif; ?>
  <?php endif; ?>
  <div id="js-edit-category-model-container" class="edit-category-model-container"></div>
  <div id="js-add-category-model-container" class="add-category-model-container"></div>
</div>