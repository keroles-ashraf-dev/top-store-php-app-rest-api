import { log, displayLoadingScreen, toggleRequestResponseMessages, delay, redirectTo } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const responseMessagesContainer = document.getElementById('js-response-container');

// ************************** functions call *************************************//

handleSetDefaultAddress(); // handle set default address request
handleAddAddress(); // handle add address request
handleEditAddress(); // handle edit address request
handleDeleteAddress(); // handle delete address request
initCountrySelect(); // init country select with current address country

// ************************** functions deceleration *************************************//

function handleSetDefaultAddress() {

  const addressesCards = Array.from(document.getElementsByClassName("js-address-card"));

  addressesCards.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(responseMessagesContainer);
      displayLoadingScreen();

      const id = e?.dataset.id;
      const requestUrl = e?.dataset.url;
      const data = new FormData();
      data.append('id', id);

      createPostRequest(data, requestUrl, 'POST');
    });
  });
}

function handleAddAddress() {

  const form = document.getElementById('js-addresses-add-form');

  form?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(responseMessagesContainer);

    const data = new FormData(form);
    const requestUrl = form.attributes['action']['value'];

    createPostRequest(data, requestUrl);
  });
}

function handleEditAddress() {

  const form = document.getElementById('js-addresses-edit-form');

  form?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(responseMessagesContainer);

    const data = new FormData(form);
    const requestUrl = form.attributes['action']['value'];

    createPostRequest(data, requestUrl);
  });
}

function handleDeleteAddress() {

  const deleteButtons = Array.from(document.getElementsByClassName("js-remove-btn"));

  deleteButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(responseMessagesContainer);

      const id = e?.dataset.id;
      const requestUrl = e?.dataset.url;
      const data = new FormData();
      data.append('id', id);

      const action = confirm("delete: " + id);

      if (action) createPostRequest(data, requestUrl, 'POST');
    });
  });
}

function createPostRequest(data, requestUrl) {
  const request = new Request(
    requestUrl,
    {
      method: 'POST',
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

  if (resData['message']) {
    toggleRequestResponseMessages(responseMessagesContainer, resData['message'], resData['success'], true);
    if (resData['success']) delay(() => toggleRequestResponseMessages(responseMessagesContainer));
  }
}

function initCountrySelect() {

  const countrySelect = document.getElementById("js-country-select");
  const addressCountry = document.getElementById("js-address-country");

  if (!countrySelect || !addressCountry) return;

  countrySelect.value = addressCountry.textContent;
}
