import { log, delay, displayScreenCover, formDataToJson, htmlSpecialChars_decode, toggleRequestResponseMessages } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const messagesContainer = document.getElementById("js-response-container");
const editModel = document.getElementById('js-edit-model-container');
const addModel = document.getElementById('js-add-model-container');
let editFormMessagesContainer;
let addFormMessagesContainer;

// ************************** functions call *************************************//

handleDelete();  // query delete buttons and send delete request
handleChangeStatus();  // query change status buttons and send change status request
handleEditFormDisplay();  // query edit buttons and send request to show edit form
handleAddFormDisplay();  // query edit buttons and send request to show edit form

// ************************** functions deceleration *************************************//

function handleDelete() {

  const deleteButtons = Array.from(document.getElementsByClassName("js-delete"));

  deleteButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(messagesContainer);

      const languageId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("delete: " + languageId);

      if (action) createDeleteRequest(languageId, url);
    });
  });
}

function createDeleteRequest(languageId, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': languageId };

  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data),
    });

  sendDeleteRequest(request);
}

function sendDeleteRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleDeleteResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleDeleteResponse(resData) {

  if (resData['success']) {
    document.getElementById(resData['languageId']).outerHTML = "";
  }

  if (resData['message']) {
    toggleRequestResponseMessages(messagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(messagesContainer));
  }

}

function handleChangeStatus() {

  const changeStatusButtons = Array.from(document.getElementsByClassName("js-status"));

  changeStatusButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(messagesContainer);

      const languageId = e?.dataset.id;
      const url = e?.dataset.url;
      const row = document.getElementById(languageId);
      const statusField = row.querySelector('#js-status-field');
      const status = statusField.textContent == 'enabled' ? 0 : 1;

      const action = confirm("change status: " + languageId);

      if (action) createChangeStatusRequest(languageId, status, url);
    });
  });
}

function createChangeStatusRequest(languageId, status, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': languageId, 'status': status };

  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data),
    });

  sendChangeStatusRequest(request);
}

function sendChangeStatusRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleChangeStatusResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleChangeStatusResponse(resData) {

  if (resData['success']) {
    const status = resData['status'] == 1 ? 'enabled' : 'disabled';
    const parent = document.getElementById(resData['languageId']);
    const statusBtn = parent.querySelector('.js-status');
    const statusField = parent.querySelector('#js-status-field');
    statusBtn.innerHTML = status == 'enabled' ? '<i class="fa-solid fa-ban">' : '<i class="fa-solid fa-circle-check">';
    statusField.textContent = status;
  }

  if (resData['message']) {
    toggleRequestResponseMessages(messagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(messagesContainer));
  }
}

function handleEditFormDisplay() {

  const editButtons = Array.from(document.getElementsByClassName("js-edit"));

  editButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(messagesContainer);

      const languageId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("edit: " + languageId);

      if (action) createEditFormDisplayRequest(languageId, url);

    });
  });
}

function createEditFormDisplayRequest(languageId, url) {
  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': languageId };

  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data),
    });

  sendEditFormDisplayRequest(request);
}

function sendEditFormDisplayRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleEditFormDisplayResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleEditFormDisplayResponse(resData) {
  if (resData['success']) {
    editModel.innerHTML += htmlSpecialChars_decode(resData['data']);

    //init edit language model and form
    initAndHandleEditFormActions();

    displayScreenCover();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(messagesContainer, resData['message'], resData['success'], true);
  }
}

function initAndHandleEditFormActions() {

  editFormMessagesContainer = editModel.querySelector('#js-edit-form-response-container');
  const editForm = editModel.querySelector('#js-edit-form');
  const downloadButton = editModel.querySelector('#js-form-json-download');
  const cancelButton = editModel.querySelector('#js-edit-form-cancel');

  // hide edit language model
  downloadButton?.addEventListener('click', (e) => {
    e.preventDefault();

    const url = downloadButton.dataset.url;
    window.location = url;
  });


  // hide edit language model
  cancelButton?.addEventListener('click', (e) => {
    e.preventDefault();

    editModel.innerHTML = "";

    displayScreenCover(false);
  });

  editForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(editFormMessagesContainer);

    const data = new FormData(editForm);

    const requestUrl = editForm.attributes['action']['value'];
    const requestMethod = editForm.attributes['method']['value'];

    createEditRequest(data, requestMethod, requestUrl);
  });

}

function createEditRequest(data, requestMethod, requestUrl) {
  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      body: data,
    });

  sendEditRequest(request);
}

function sendEditRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleEditResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleEditResponse(resData) {

  if (resData['success']) {
    alert('language edited successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(editFormMessagesContainer, resData['message'], resData['success'], true);
  }
}

function handleAddFormDisplay() {

  const addButton = document.getElementById("js-add");

  addButton?.addEventListener('click', _ => {

    toggleRequestResponseMessages(messagesContainer);

    const url = addButton?.dataset.url;

    const action = confirm("add");

    if (action) createAddFormDisplayRequest(url);

  });
}

function createAddFormDisplayRequest(url) {
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

  sendAddFormDisplayRequest(request);
}

function sendAddFormDisplayRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleAddFormDisplayResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleAddFormDisplayResponse(resData) {
  if (resData['success']) {
    addModel.innerHTML += htmlSpecialChars_decode(resData['data']);

    //init add language model and form
    initAndHandleAddFormActions();

    displayScreenCover();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(messagesContainer, resData['message'], resData['success'], true);
  }
}

function initAndHandleAddFormActions() {

  addFormMessagesContainer = addModel.querySelector('#js-add-form-response-container');
  const addForm = addModel.querySelector('#js-add-form');
  const cancelButton = addModel.querySelector('#js-add-form-cancel');

  // hide add language model
  cancelButton?.addEventListener('click', (e) => {
    e.preventDefault();

    addModel.innerHTML = "";

    displayScreenCover(false);
  });

  addForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(addFormMessagesContainer);

    const data = new FormData(addForm);

    const requestUrl = addForm.attributes['action']['value'];
    const requestMethod = addForm.attributes['method']['value'];

    createAddRequest(data, requestMethod, requestUrl);
  });

}

function createAddRequest(data, requestMethod, requestUrl) {
  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      body: data,
    });

  sendAddRequest(request);
}

function sendAddRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleAddResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleAddResponse(resData) {

  if (resData['success']) {
    alert('language added successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(addFormMessagesContainer, resData['message'], resData['success'], true);
  }
}