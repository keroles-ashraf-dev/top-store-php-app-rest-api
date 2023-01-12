<div class="profile">
  <h2 data-translationKey="yourAccount">Your Account</h2>
  <div class="content">
    <div class="card orders">
      <a href="<?php echo url('/profile/orders') ?>">
        <div class="img-container">
          <img src="<?php echo assets('/common/images/orders-card-img.png') ?>" alt="">
        </div>
        <div class="txt-container">
          <h3 data-translationKey="yourOrders">Your Orders</h3>
          <span data-translationKey="yourOrdersHint">Track, return, or buy things again</span>
        </div>
      </a>
    </div>
    <div class="card data">
      <a href="<?php echo url('/profile/data') ?>">
        <div class="img-container">
          <img src="<?php echo assets('/common/images/data-card-img.png') ?>" alt="">
        </div>
        <div class="txt-container">
          <h3 data-translationKey="yourData">Your Data</h3>
          <span data-translationKey="yourDataHint">Edit name, email, and mobile number</span>
        </div>
      </a>
    </div>
    <!-- <div class="card payments">
      <a href="<?php echo url('/profile/payments') ?>">
        <div class="img-container">
          <img src="<?php echo assets('/common/images/payments-card-img.png') ?>" alt="">
        </div>
        <div class="txt-container">
          <h3 data-translationKey="yourPayments">Your Payments</h3>
          <span data-translationKey="yourPaymentsHint">Manage payment methods and settings</span>
        </div>
      </a>
    </div> -->
    <div class="card addresses">
      <a href="<?php echo url('/profile/addresses') ?>">
        <div class="img-container">
          <img src="<?php echo assets('/common/images/addresses-card-img.png') ?>" alt="">
        </div>
        <div class="txt-container">
          <h3 data-translationKey="yourAddresses">Your Addresses</h3>
          <span data-translationKey="yourAddressesHint">Manage your addresses for orders</span>
        </div>
      </a>
    </div>
    <div class="card support">
      <a href="<?php echo url('/support/chat') ?>">
        <div class="img-container">
          <img src="<?php echo assets('/common/images/contact-card-img.png') ?>" alt="">
        </div>
        <div class="txt-container">
          <h3 data-translationKey="contactUs">Contact Us</h3>
          <span data-translationKey="supportHint">Contact our customer service via chat</span>
        </div>
      </a>
    </div>
  </div>
</div>