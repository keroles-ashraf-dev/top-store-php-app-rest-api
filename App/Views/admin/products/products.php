<div class="products-page">
  <h1>Products Management</h1>
  <div class="search-container">
    <form action="<?php echo url('/admin/products/search'); ?>" method="GET">
      <input type="text" name="search-key" placeholder="Search by name, id or category id" value="<?php echo isset($searchKey) ? $searchKey : '' ?>">
      <button type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>
  <div class="add-product-button-container">
    <Button id="js-products-add" data-url="<?php echo url('/admin/products/add'); ?>">Add product</Button>
  </div>
  <div class="response-container" id="js-response-container"></div>
  <?php if (empty($products)) : ?>
    <div class="empty">No Products Found</div>
  <?php else : ?>
    <table>
      <tr>
        <th data-translationKey="id">Id</th>
        <th data-translationKey="name">Name</th>
        <th data-translationKey="categoryId">Category id</th>
        <th data-translationKey="categoryName">Category name</th>
        <th data-translationKey="description">Description</th>
        <th data-translationKey="price">Price</th>
        <th data-translationKey="availableCounts">Available counts</th>
        <th data-translationKey="status">Status</th>
        <th data-translationKey="actions">Actions</th>
      </tr>
      <?php
      foreach ($products as $product) {
        $html = '
          <tr id="?id">
          <td>?id</td>
          <td>?name</td>
          <td>?categoryId</td>
          <td>?categoryName</td>
          <td>?description</td>
          <td>?price</td>
          <td>?availableCount</td>
          <td id="js-product-status-field">?status</td>
          <td>
          <button class="js-products-edit" data-id="?id" data-url="?editUrl"><i class="fa-solid fa-edit"></i></button>
          <button class="js-products-status" data-id="?id" data-url="?changeStatusUrl"><i class="fa-solid ?changeStatusActionIcon"></i></button>
          <button class="js-products-delete" data-id="?id" data-url="?deleteUrl"><i class="fa-solid fa-trash"></i></button>
          </td>
          </tr>';
        $values = [
          'id' => $product->id,
          'name' => read_more($product->name, 3),
          'categoryId' => $product->category_id,
          'categoryName' => $product->category_name ?: '',
          'description' => read_more($product->description,5),
          'price' => $product->price ?: '',
          'availableCount' => $product->available_count ?: '',
          'status' => $product->status == 1 ? 'enabled' : 'disabled',
          'editUrl' => url('admin/products/edit') . '/' . $product->id,
          'changeStatusUrl' => url('admin/products/change-status'),
          'changeStatusActionIcon' => $product->status == 1 ? 'fa-ban' : 'fa-circle-check',
          'deleteUrl' => url('admin/products/delete'),
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
  <div id="js-edit-product-model-container" class="edit-product-model-container"></div>
  <div id="js-add-product-model-container" class="add-product-model-container"></div>
</div>