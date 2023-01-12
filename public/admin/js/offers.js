import { log, delay, displayScreenCover, formDataToJson, htmlSpecialChars_decode, toggleRequestResponseMessages } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const offersMessagesContainer = document.getElementById("js-response-container");
const addSliderModel = document.getElementById('js-add-slider-model-container');
const addDealModel = document.getElementById('js-add-deal-model-container');
let addSliderFormMessagesContainer;
let addDealFormMessagesContainer;

// ************************** functions call *************************************//

handleDeleteSlider();  // query delete buttons and send delete request
handleSliderChangeStatus();  // query change status buttons and send change status request
handleAddSliderFormDisplay();  // send request to get add slider form

handleDeleteDeal();  // query delete buttons and send delete request
handleDealChangeStatus();  // query change status buttons and send change status request
handleAddDealFormDisplay();  // send request to get add slider form

// ************************** functions deceleration *************************************//

function handleDeleteSlider() {

  const deleteButtons = Array.from(document.getElementsByClassName("js-slider-delete"));

  deleteButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(offersMessagesContainer);

      const sliderId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("delete: " + sliderId);

      if (action) createDeleteSliderRequest(sliderId, url);
    });
  });
}

function createDeleteSliderRequest(sliderId, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': sliderId };

  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data),
    });

  sendDeleteSliderRequest(request);
}

function sendDeleteSliderRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleDeleteSliderResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleDeleteSliderResponse(resData) {

  if (resData['success']) {
    alert('Slider deleted successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(offersMessagesContainer, resData['message'], resData['success'], true);
  }

}

function handleSliderChangeStatus() {

  const changeStatusButtons = Array.from(document.getElementsByClassName("js-slider-status"));

  changeStatusButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(offersMessagesContainer);

      const sliderId = e?.dataset.id;
      const url = e?.dataset.url;
      const row = document.getElementById(sliderId);
      const statusField = row.querySelector('#js-slider-status-field');
      const status = statusField.textContent == 'enabled' ? 0 : 1;

      const action = confirm("change status: " + sliderId);

      if (action) createChangeSliderStatusRequest(sliderId, status, url);
    });
  });
}

function createChangeSliderStatusRequest(sliderId, status, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': sliderId, 'status': status };

  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data),
    });

  sendChangeSliderStatusRequest(request);
}

function sendChangeSliderStatusRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleChangeSliderStatusResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleChangeSliderStatusResponse(resData) {
  if (resData['success']) {
    const status = resData['status'] == 1 ? 'enabled' : 'disabled';
    const parent = document.getElementById(resData['sliderId']);
    const statusBtn = parent.querySelector('.js-slider-status');
    const statusField = parent.querySelector('#js-slider-status-field');
    statusBtn.innerHTML = status == 'enabled' ? '<i class="fa-solid fa-ban">' : '<i class="fa-solid fa-circle-check">';
    statusField.textContent = status;
  }

  if (resData['message']) {
    toggleRequestResponseMessages(offersMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(offersMessagesContainer));
  }
}

function handleAddSliderFormDisplay() {

  const addButton = document.getElementById("js-slider-add");

  addButton?.addEventListener('click', _ => {

    toggleRequestResponseMessages(offersMessagesContainer);

    const url = addButton?.dataset.url;

    const action = confirm("add new slider");

    if (action) createAddSliderFormDisplayRequest(url);

  });
}

function createAddSliderFormDisplayRequest(url) {
  const requestUrl = url;
  const requestMethod = 'GET';

  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
    });

  sendAddSliderFormDisplayRequest(request);
}

function sendAddSliderFormDisplayRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleAddSliderFormDisplayResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleAddSliderFormDisplayResponse(resData) {
  if (resData['success']) {
    addSliderModel.innerHTML += htmlSpecialChars_decode(resData['data']);

    //init add model and form
    initAndHandleAddSliderFormActions();

    displayScreenCover();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(offersMessagesContainer, resData['message'], resData['success'], true);
  }
}

function initAndHandleAddSliderFormActions() {

  addSliderFormMessagesContainer = addSliderModel.querySelector('#js-add-form-response-container');
  const addForm = addSliderModel.querySelector('#js-add-form');
  const addImageInput = addSliderModel.querySelector('#js-add-form-image-input');
  const addImageContainer = addSliderModel.querySelector('#js-add-image-container');
  const cancelButton = addSliderModel.querySelector('#js-add-form-cancel');

  // hide add model
  cancelButton?.addEventListener('click', (e) => {
    e.preventDefault();

    addSliderModel.innerHTML = "";

    displayScreenCover(false);
  });

  // listen to image pickup
  addImageInput?.addEventListener('change', (e) => {

    addImageContainer.innerHTML = '';

    const files = Array.from(addImageInput.files);

    files.forEach(file => {

      const element = document.createElement('img');
      element.src = URL.createObjectURL(file);
      addImageContainer.appendChild(element);
    });
  });

  addForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(addSliderFormMessagesContainer);

    const data = new FormData(addForm);

    const requestUrl = addForm.attributes['action']['value'];
    const requestMethod = addForm.attributes['method']['value'];

    createAddSliderRequest(data, requestMethod, requestUrl);
  });

}

function createAddSliderRequest(data, requestMethod, requestUrl) {
  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      body: data,
    });

  sendAddSliderRequest(request);
}

function sendAddSliderRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleAddSliderResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleAddSliderResponse(resData) {

  if (resData['success']) {
    alert('Slider created successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(addSliderFormMessagesContainer, resData['message'], resData['success'], true);
  }
}

/********************************************  today's deals  *************************************/

function handleDeleteDeal() {

  const deleteButtons = Array.from(document.getElementsByClassName("js-deal-delete"));

  deleteButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(offersMessagesContainer);

      const DealId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("delete: " + DealId);

      if (action) createDeleteDealRequest(DealId, url);
    });
  });
}

function createDeleteDealRequest(DealId, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': DealId };

  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data),
    });

  sendDeleteDealRequest(request);
}

function sendDeleteDealRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleDeleteDealResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleDeleteDealResponse(resData) {

  if (resData['success']) {
    document.getElementById(resData['dealId']).outerHTML = "";
  }

  if (resData['message']) {
    toggleRequestResponseMessages(offersMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(usersMessagesContainer));
  }
}

function handleDealChangeStatus() {

  const changeStatusButtons = Array.from(document.getElementsByClassName("js-deal-status"));

  changeStatusButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(offersMessagesContainer);

      const dealId = e?.dataset.id;
      const url = e?.dataset.url;
      const row = document.getElementById(dealId);
      const statusField = row.querySelector('#js-deal-status-field');
      const status = statusField.textContent == 'enabled' ? 0 : 1;

      const action = confirm("change status: " + dealId);

      if (action) createChangeDealStatusRequest(dealId, status, url);
    });
  });
}

function createChangeDealStatusRequest(DealId, status, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': DealId, 'status': status };

  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data),
    });

  sendChangeDealStatusRequest(request);
}

function sendChangeDealStatusRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleChangeDealStatusResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleChangeDealStatusResponse(resData) {
  if (resData['success']) {
    const status = resData['status'] == 1 ? 'enabled' : 'disabled';
    const parent = document.getElementById(resData['dealId']);
    const statusBtn = parent.querySelector('.js-deal-status');
    const statusField = parent.querySelector('#js-deal-status-field');
    statusBtn.innerHTML = status == 'enabled' ? '<i class="fa-solid fa-ban">' : '<i class="fa-solid fa-circle-check">';
    statusField.textContent = status;
  }

  if (resData['message']) {
    toggleRequestResponseMessages(offersMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(offersMessagesContainer));
  }
}

function handleAddDealFormDisplay() {

  const addButton = document.getElementById("js-deal-add");

  addButton?.addEventListener('click', _ => {

    toggleRequestResponseMessages(offersMessagesContainer);

    const url = addButton?.dataset.url;

    const action = confirm("add new deal");

    if (action) createAddDealFormDisplayRequest(url);

  });
}

function createAddDealFormDisplayRequest(url) {
  const requestUrl = url;
  const requestMethod = 'GET';

  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
    });

  sendAddDealFormDisplayRequest(request);
}

function sendAddDealFormDisplayRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleAddDealFormDisplayResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleAddDealFormDisplayResponse(resData) {
  if (resData['success']) {
    addDealModel.innerHTML += htmlSpecialChars_decode(resData['data']);

    //init add model and form
    initAndHandleAddDealFormActions();

    displayScreenCover();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(offersMessagesContainer, resData['message'], resData['success'], true);
  }
}

function initAndHandleAddDealFormActions() {

  addDealFormMessagesContainer = addDealModel.querySelector('#js-add-form-response-container');
  const addForm = addDealModel.querySelector('#js-add-form');
  const cancelButton = addDealModel.querySelector('#js-add-form-cancel');

  // hide add model
  cancelButton?.addEventListener('click', (e) => {
    e.preventDefault();

    addDealModel.innerHTML = "";

    displayScreenCover(false);
  });

  addForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(addDealFormMessagesContainer);

    let data = new FormData(addForm);
    data = formDataToJson(data);

    const requestUrl = addForm.attributes['action']['value'];
    const requestMethod = addForm.attributes['method']['value'];

    createAddDealRequest(data, requestMethod, requestUrl);
  });
}

function createAddDealRequest(data, requestMethod, requestUrl) {
  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
      body: data,
    });

  sendAddDealRequest(request);
}

function sendAddDealRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleAddDealResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleAddDealResponse(resData) {

  if (resData['success']) {
    alert('Deal created successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(addDealFormMessagesContainer, resData['message'], resData['success'], true);
  }
}