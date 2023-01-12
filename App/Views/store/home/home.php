<div class="home">
  <div class="slider">
    <?php
    $html = '
    <div class="item home-slider-fade-animation">
    <img src="?image">
    </div>';
    foreach ($sliderImages as $image) {
      echo inject_html($html, ['image' => assets('common/images/' . $image->image)]);
    }
    ?>
    <div class="prev" id="js-home-slider-prev">
      <i class="fa-solid fa-chevron-left"></i>
    </div>
    <div class="next" id="js-home-slider-next">
      <i class="fa-solid fa-chevron-right"></i>
    </div>
  </div>
  <div class="categories-deals-container padding-container">
    <?php
    $html = '
      <div class="category-card">
      <a href="?categoryViewUrl">
      <span>?name</span>
      <img src="?image" alt="">
      </a>
      </div>
      ';
    foreach ($subCategories as $category) {
      $values = [
        'name' => $category->name,
        'image' => assets('common/images/' . $category->image),
        'categoryViewUrl' => url('category?id=') . $category->id,
      ];
      echo inject_html($html, $values);
    }
    ?>
  </div>
  <div class="today-deals-container margin-container">
    <span class="title" data-translationKey="todayDeals">Today's Deals</span>
    <div class="products-row">
      <?php
      $html = '
        <div class="product-card">
        <a href="?overviewUrl">
        <div class="img-container">
        <img src="?image" alt="">
        </div>
        <span class="name">?name</span>
        <span class="discount">?discountPercentage% off</span>
        <span class="price">?discountedPrice <strong>$</strong></span>
        <span class="list-price">list price: <s>?price $</s></span>
        <div class="rating-container">
            <span class="js-rating-container" data-rating="?rating"></span>
            <span>?ratersCount</span>
        </div>
        </a>
        </div>';
      foreach ($deals as $deal) {
        $values = [
          'name' => $deal->name,
          'image' => assets('common/images/' . $deal->image),
          'discountedPrice' => $deal->discounted_price,
          'price' => $deal->price,
          'discountPercentage' => ceil((($deal->price - $deal->discounted_price) / $deal->price) * 100),
          'rating' => $deal->rating,
          'ratersCount' => $deal->raters_count,
          'overviewUrl' => url('product?id=') . $deal->product_id,
        ];
        echo inject_html($html, $values);
      }
      ?>
    </div>
  </div>
</div>