<div class="users-page">
  <h1>Users Management</h1>
  <div class="search-container">
    <form action="<?php echo url('/admin/users/search'); ?>" method="POST">
      <input type="text" name="search" placeholder="Search by phone, email or id" value="<?php echo isset($searchKey) ? $searchKey : '' ?>">
      <button type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>
  <div class="response-container" id="js-response-container"></div>
  <?php if (empty($users)) : ?>
    <div class="empty">No Users Found</div>
  <?php else : ?>
    <table>
      <tr>
        <th data-translationKey="id">Id</th>
        <th data-translationKey="role">Role</th>
        <th data-translationKey="firstName">First name</th>
        <th data-translationKey="lastName">Last name</th>
        <th data-translationKey="email">Email</th>
        <th data-translationKey="emailVerified">Email verified</th>
        <th data-translationKey="phone">Phone</th>
        <th data-translationKey="phoneVerified">Phone verified</th>
        <th data-translationKey="joined">Joined</th>
        <th data-translationKey="status">Status</th>
        <th data-translationKey="ip">Ip</th>
        <th data-translationKey="actions">Actions</th>
      </tr>
      <?php
      foreach ($users as $user) {
        $html = '
          <tr id="?id">
          <td>?id</td>
          <td>?role</td>
          <td>?firstName</td>
          <td>?lastName</td>
          <td>?email</td>
          <td>?isEmailVerified</td>
          <td>?phone</td>
          <td>?isPhoneVerified</td>
          <td>?created</td>
          <td id="js-user-status-field">?status</td>
          <td>?ip</td>
          <td>
          <button class="js-users-edit" data-id="?id" data-url="?editUrl"><i class="fa-solid fa-edit"></i></button>
          <button class="js-users-status" data-id="?id" data-url="?changeStatusUrl"><i class="fa-solid ?changeStatusActionIcon"></i></button>
          <button class="js-users-delete" data-id="?id" data-url="?deleteUrl"><i class="fa-solid fa-trash"></i></button>
          </td>
          </tr>';
        $values = [
          'id' => $user->id,
          'role' => $user->role,
          'firstName' => $user->first_name,
          'lastName' => $user->last_name,
          'email' => $user->email,
          'isEmailVerified' => $user->email_verified == 1 ? 'verified' : 'unverified',
          'phone' => $user->phone,
          'isPhoneVerified' => $user->phone_verified == 1 ? 'verified' : 'unverified',
          'created' => date('d/m/Y', $user->created),
          'status' => $user->status == 1 ? 'enabled' : 'disabled',
          'ip' => $user->ip,
          'editUrl' => url('admin/users/edit') . '/' . $user->id,
          'changeStatusUrl' => url('admin/users/change-status'),
          'changeStatusActionIcon' => $user->status == 1 ? 'fa-user-slash' : 'fa-user',
          'deleteUrl' => url('admin/users/delete'),
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
  <div id="js-edit-user-model-container" class="edit-user-model-container"></div>
</div>