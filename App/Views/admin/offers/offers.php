<div class="offers-page">
  <h1>Offers Management</h1>
  <div class="response-container" id="js-response-container"></div>
  <div class="slider">
    <div class="title-add-button-container">
      <div class="title">
        <h2>Slider Items</h2>
        <span></span>
      </div>
      <Button id="js-slider-add" data-url="<?php echo url('/admin/offers/add-slider'); ?>">Add item</Button>
    </div>
    <?php if (empty($sliders)) : ?>
    <div class="empty">No Sliders Found</div>
  <?php else : ?>
    <table>
      <tr>
        <th data-translationKey="id">Id</th>
        <th data-translationKey="name">Name</th>
        <th data-translationKey="order">Order</th>
        <th data-translationKey="status">Status</th>
        <th data-translationKey="actions">Actions</th>
      </tr>
      <?php
      foreach ($sliders as $slider) {
        $html = '
          <tr id="?id">
          <td>?id</td>
          <td>?name</td>
          <td>?order</td>
          <td id="js-slider-status-field">?status</td>
          <td>
          <button class="js-slider-status" data-id="?id" data-url="?changeStatusUrl"><i class="fa-solid ?changeStatusActionIcon"></i></button>
          <button class="js-slider-delete" data-id="?id" data-url="?deleteUrl"><i class="fa-solid fa-trash"></i></button>
          </td>
          </tr>';
        $values = [
          'id' => $slider->id,
          'name' => read_more($slider->name, 3),
          'order' => $slider->order,
          'status' => $slider->status == 1 ? 'enabled' : 'disabled',
          'changeStatusUrl' => url('admin/offers/change-slider-status'),
          'changeStatusActionIcon' => $slider->status == 1 ? 'fa-ban' : 'fa-circle-check',
          'deleteUrl' => url('admin/offers/delete-slider'),
        ];
        echo inject_html($html, $values);
      }
      ?>
    </table>
  <?php endif; ?>
  </div>
  <div class="deals">
    <div class="title-add-button-container">
      <div class="title">
        <h2>Today's Deals</h2>
        <span></span>
      </div>
      <Button id="js-deal-add" data-url="<?php echo url('/admin/offers/add-deal'); ?>">Add item</Button>
    </div>
    <?php if (empty($deals)) : ?>
    <div class="empty">No Deals Found</div>
  <?php else : ?>
    <table>
      <tr>
        <th data-translationKey="id">Id</th>
        <th data-translationKey="productId">Product id</th>
        <th data-translationKey="name">Name</th>
        <th data-translationKey="price">price</th>
        <th data-translationKey="discountedPrice">Discounted price</th>
        <th data-translationKey="status">Status</th>
        <th data-translationKey="actions">Actions</th>
      </tr>
      <?php
      foreach ($deals as $deal) {
        $html = '
          <tr id="?id">
          <td>?id</td>
          <td>?productId</td>
          <td>?name</td>
          <td>?price</td>
          <td>?discountedPrice</td>
          <td id="js-deal-status-field">?status</td>
          <td>
          <button class="js-deal-status" data-id="?id" data-url="?changeStatusUrl"><i class="fa-solid ?changeStatusActionIcon"></i></button>
          <button class="js-deal-delete" data-id="?id" data-url="?deleteUrl"><i class="fa-solid fa-trash"></i></button>
          </td>
          </tr>';
        $values = [
          'id' => $deal->id,
          'productId' => $deal->product_id,
          'name' => read_more($deal->name, 3),
          'price' => $deal->price,
          'discountedPrice' => $deal->discounted_price,
          'status' => $deal->status == 1 ? 'enabled' : 'disabled',
          'changeStatusUrl' => url('admin/offers/change-deal-status'),
          'changeStatusActionIcon' => $deal->status == 1 ? 'fa-ban' : 'fa-circle-check',
          'deleteUrl' => url('admin/offers/delete-deal'),
        ];
        echo inject_html($html, $values);
      }
      ?>
    </table>
  <?php endif; ?>
  </div>
  <div id="js-add-slider-model-container" class="model-container"></div>
  <div id="js-add-deal-model-container" class="model-container"></div>
</div>