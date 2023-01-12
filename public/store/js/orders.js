import { log, displayLoadingScreen, redirectTo } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const responseMessagesContainer = document.getElementById('js-response-container');

// ************************** functions call *************************************//

setOrderCancelsBtn(); // init cancel buttons

// ************************** functions deceleration *************************************//

function setOrderCancelsBtn() {

  const cancelsBtn = Array.from(document.getElementsByClassName('js-cancel-btn'));

  cancelsBtn.forEach(e => {

    e?.addEventListener('click', _ => {

      displayLoadingScreen();

      const orderId = e?.dataset.id;
      const requestUrl = e?.dataset.url;
      const requestMethod = 'POST';
      const data = new FormData();
      data.set('id', orderId);

      createApiRequest(data, requestMethod, requestUrl);
    });
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

  displayLoadingScreen(false);

  if (resData['redirectTo']) {

    if (!resData['redirectToDelay']) return redirectTo(resData['redirectTo']);

    delay(() => redirectTo(resData['redirectTo']), resData['redirectToDelay'])
  }

  if (resData['success']) {
    updateOrderStatus(resData['orderId'])
  }

  if (resData['message']) {
    toggleRequestResponseMessages(responseMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(responseMessagesContainer), 2000);
  }
}

function updateOrderStatus(id) {

  const parent = document.getElementById(id);
  const status = parent.querySelector('.js-order-status');
  const cancelBtn = parent.querySelector('.js-cancel-btn');
  status.textContent = 'canceled';
  cancelBtn.classList.add('hide');
}