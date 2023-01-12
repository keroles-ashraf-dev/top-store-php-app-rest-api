import { log, displayLoadingScreen, redirectTo } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const responseMessagesContainer = document.getElementById('js-response-container');

// ************************** functions call *************************************//

setCartIncrementBtn(); // init increment product in cart button click listener

setCartDecrementBtn(); // init decrement product in cart button click listener

checkout(); // checkout button click listener

// ************************** functions deceleration *************************************//

function setCartIncrementBtn() {

  const incrementBtn = Array.from(document.getElementsByClassName('js-cart-increment'));

  incrementBtn.forEach(e => {

    e?.addEventListener('click', _ => {

      displayLoadingScreen();

      const productId = e?.dataset.id;
      const requestUrl = e?.dataset.url;
      const requestMethod = 'POST';
      const data = new FormData();
      data.set('id', productId);

      createApiRequest(data, requestMethod, requestUrl);
    });
  });
}

function setCartDecrementBtn() {

  const incrementBtn = Array.from(document.getElementsByClassName('js-cart-decrement'));

  incrementBtn.forEach(e => {

    e?.addEventListener('click', _ => {

      displayLoadingScreen();

      const productId = e?.dataset.id;
      const requestUrl = e?.dataset.url;
      const requestMethod = 'POST';
      const data = new FormData();
      data.set('id', productId);

      createApiRequest(data, requestMethod, requestUrl);
    });
  });
}

function checkout() {

  const checkoutBtn = document.getElementById('js-checkout-btn');

  checkoutBtn?.addEventListener('click', e => {

    displayLoadingScreen();

    const requestUrl = checkoutBtn?.dataset.url;
    const requestMethod = 'POST';

    const data = new FormData();

    const cash = document.getElementById('js-payment-cash');

    if (cash?.checked) data.set('payment-method', 'cash');
    else data.set('payment-method', 'digital');

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
    if (resData['increment']) incrementCart(resData['productId']);
    if (resData['decrement']) decrementCart(resData['productId']);
    updateCart(resData['subtotalPrice']);
    updateNavCart(resData['cartCount']);
    displayLoadingScreen(false);
  }

  if (resData['message']) {
    toggleRequestResponseMessages(responseMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(responseMessagesContainer), 2000);
  }
}

function incrementCart(productId) {

  const parent = document.getElementById(productId);
  const count = parent.querySelector('#js-product-count');
  count.innerText = Number(count.innerText) + 1;
}

function decrementCart(productId) {

  const parent = document.getElementById(productId);
  const count = parent.querySelector('#js-product-count');
  const newCount = Number(count.innerText) - 1;

  if (newCount < 1) {
    parent.outerHTML = "";
    return;
  }
  count.innerText = newCount;
}

function updateCart(subtotal) {

  const subtotalContainer = document.getElementById('js-cart-subtotal');
  const itemsContainer = document.getElementById('js-cart-items');
  const vatContainer = document.getElementById('js-cart-vat');
  const vatPercent = vatContainer.dataset.vat;

  subtotalContainer.innerText = subtotal;
  itemsContainer.innerText = subtotal;
  vatContainer.innerText = (subtotal * vatPercent) / 100;
}

function updateNavCart(count) {

  const cart = document.getElementById('js-nav-cart-box-count');
  cart.innerText = count;
}