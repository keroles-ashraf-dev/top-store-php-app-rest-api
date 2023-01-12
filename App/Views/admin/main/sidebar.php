  <aside class="sidebar">
    <ul class="menu">
      <li <?php if (str_starts_with($path, '/admin/offers') || $path == '/admin') echo 'class="active"'; ?>">
        <a href="<?php echo url('/admin/offers'); ?>">
          <i class="fa fa-flag"></i> <span data-translationKey="offers">Offers</span>
        </a>
      </li>
      <li <?php if (str_starts_with($path, '/admin/users')) echo 'class="active"'; ?>">
        <a href="<?php echo url('/admin/users'); ?>">
          <i class="fa fa-user"></i> <span data-translationKey="users">Users</span>
        </a>
      </li>
      <li <?php if (str_starts_with($path, '/admin/categories')) echo 'class="active"'; ?>">
        <a href="<?php echo url('/admin/categories'); ?>">
          <i class="fa fa-book"></i> <span data-translationKey="catagories">Categories</span>
        </a>
      </li>
      <li <?php if (str_starts_with($path, '/admin/products')) echo 'class="active"'; ?>">
        <a href="<?php echo url('/admin/products'); ?>">
          <i class="fa fa-basket-shopping"></i> <span data-translationKey="products">Products</span>
        </a>
      </li>
      <li <?php if (str_starts_with($path, '/support')) echo 'class="active"'; ?>">
        <a href="<?php echo url('/admin/support/chat'); ?>">
        <i class="fa-solid fa-headset"></i> <span data-translationKey="support">Support</span>
        </a>
      </li>
      <li <?php if (str_starts_with($path, '/admin/languages')) echo 'class="active"'; ?>">
        <a href="<?php echo url('/admin/languages'); ?>">
          <i class="fa fa-globe"></i> <span data-translationKey="languages">Languages</span>
        </a>
      </li>
      <li <?php if (str_starts_with($path, '/admin/settings')) echo 'class="active"'; ?>">
        <a href="<?php echo url('/admin/settings'); ?>">
          <i class="fa fa-gear"></i> <span data-translationKey="settings">Settings</span>
        </a>
      </li>
    </ul>
  </aside>