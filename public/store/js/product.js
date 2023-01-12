import { log, displayLoadingScreen, redirectTo } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const responseMessagesContainer = document.getElementById('js-response-container');

// ************************** functions call *************************************//

setProductRating(); // set rating view for product

setProductImages(); // init product images listener to expand it on hover

setAddToCartBtn() // init add to cart button click listener

// ************************** functions deceleration *************************************//

function setProductRating() {

  const ratingContainers = Array.from(document.getElementsByClassName('js-rating-container'));

  ratingContainers?.forEach(e => {

    const rating = e?.dataset.rating;
    const ratingInt = Number(rating.split('.')[0]);
    const ratingFraction = Number(rating.split('.')[1]);
    let fractionAdded = false;

    for (let i = 1; i <= 5; i++) {

      if (i <= ratingInt) {
        const star = '<i class="fa-solid fa-star"></i>';
        e.innerHTML += star;
        continue;
      }

      if (ratingFraction > 0 && !fractionAdded) {
        fractionAdded = true;
        const star = '<i class="fa-regular fa-star-half-stroke"></i>';
        e.innerHTML += star;
        continue;
      }

      const star = '<i class="fa-regular fa-star"></i>';
      e.innerHTML += star;
    }
  });
}

function setProductImages() {

  const expandedImg = document.getElementById('js-product-expanded-img');
  const thumbnails = Array.from(document.getElementsByClassName('js-product-thumbnail'));

  thumbnails?.forEach((e, i) => {

    // init selected element
    if (i == 0) e.parentElement.classList.add('product-thumbnail-selected');

    e?.addEventListener('mouseover', _ => {

      // if it's already have selected class then return
      if (e?.classList.contains('product-thumbnail-selected')) return;

      // remove selected class from elements
      thumbnails?.forEach(e2 => {
        e2?.parentElement.classList.remove('product-thumbnail-selected');
      });
      // add selected class to hovered one
      e.parentElement.classList.add('product-thumbnail-selected');
      expandedImg.src = e.src;
    });

  });
}

function setAddToCartBtn() {

  const addToCartBtn = document.getElementById('js-add-to-cart-btn');

  addToCartBtn?.addEventListener('click', _ => {

    displayLoadingScreen();

    const productId = addToCartBtn?.dataset.id;
    const requestUrl = addToCartBtn?.dataset.url;
    const requestMethod = 'POST';
    const data = new FormData();
    data.set('id', productId);

    createApiRequest(data, requestMethod, requestUrl);
  });
}

function createApiRequest(data, requestMethod, requestUrl) {
  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      body: data,
    });

  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleApiResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleApiResponse(resData) {

  if (resData['redirectTo']) {

    if (!resData['redirectToDelay']) return redirectTo(resData['redirectTo']);

    delay(() => redirectTo(resData['redirectTo']), resData['redirectToDelay'])
  }

  if (resData['success']) {
    updateCart(resData['cartCount'])
    displayLoadingScreen(false);
  }

  if (resData['message']) {
    toggleRequestResponseMessages(responseMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(responseMessagesContainer), 2000);
  }
}

function updateCart(count) {

  const cart = document.getElementById('js-nav-cart-box-count');
  cart.innerText = count;
}