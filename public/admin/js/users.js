import { log, delay, displayScreenCover, formDataToJson, htmlSpecialChars_decode, toggleRequestResponseMessages } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const usersMessagesContainer = document.getElementById("js-response-container");
const editUserModel = document.getElementById('js-edit-user-model-container');
let editUserFormMessagesContainer;

// ************************** functions call *************************************//

handleDeleteUser();  // query delete buttons and send delete request
handleUserChangeStatus();  // query change status buttons and send change status request
handleEditFormDisplay();  // query edit user buttons and send request to show edit form

// ************************** functions deceleration *************************************//

function handleDeleteUser() {

  const deleteButtons = Array.from(document.getElementsByClassName("js-users-delete"));

  deleteButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(usersMessagesContainer);

      const userId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("delete: " + userId);

      if (action) createDeleteRequest(userId, url);
    });
  });
}

function createDeleteRequest(userId, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': userId };

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
    document.getElementById(resData['userId']).outerHTML = "";
  }

  if (resData['message']) {
    toggleRequestResponseMessages(usersMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(usersMessagesContainer));
  }

}

function handleUserChangeStatus() {

  const changeStatusButtons = Array.from(document.getElementsByClassName("js-users-status"));

  changeStatusButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(usersMessagesContainer);

      const userId = e?.dataset.id;
      const url = e?.dataset.url;
      const row = document.getElementById(userId);
      const statusField = row.querySelector('#js-user-status-field');
      const userStatus = statusField.textContent == 'enabled' ? 0 : 1;

      const action = confirm("change status: " + userId);

      if (action) createChangeStatusRequest(userId, userStatus, url);
    });
  });
}

function createChangeStatusRequest(userId, userStatus, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': userId, 'status': userStatus };

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
    const parent = document.getElementById(resData['userId']);
    const statusBtn = parent.querySelector('.js-users-status');
    const statusField = parent.querySelector('#js-user-status-field');
    statusBtn.innerHTML = status == 'enabled' ? '<i class="fa-solid fa-user-slash">' : '<i class="fa-solid fa-user">';
    statusField.textContent = status;
  }

  if (resData['message']) {
    toggleRequestResponseMessages(usersMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(usersMessagesContainer));
  }
}

function handleEditFormDisplay() {

  const editButtons = Array.from(document.getElementsByClassName("js-users-edit"));

  editButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(usersMessagesContainer);

      const userId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("edit: " + userId);

      if (action) createEditFormDisplayRequest(userId, url);

    });
  });
}

function createEditFormDisplayRequest(userId, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': userId };

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
    editUserModel.innerHTML += htmlSpecialChars_decode(resData['data']);

    //init edit user model and form
    initAndHandleEditUserFormActions();

    displayScreenCover();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(usersMessagesContainer, resData['message'], resData['success'], true);
  }
}

function initAndHandleEditUserFormActions() {

  editUserFormMessagesContainer = editUserModel.querySelector('#js-edit-form-response-container');
  let editUserForm = editUserModel.querySelector('#js-edit-user-form');
  let cancelButton = editUserModel.querySelector('#js-edit-form-cancel');

  // hide edit user model
  cancelButton?.addEventListener('click', (e) => {
    e.preventDefault();

    editUserModel.innerHTML = "";

    displayScreenCover(false);
  });

  editUserForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(editUserFormMessagesContainer);

    let data = new FormData(editUserForm);

    if (!(data.get('password')) || !(data.get('confirm-password'))) {
      data.delete('password');
      data.delete('confirm-password');
    }

    const requestUrl = editUserForm.attributes['action']['value'];
    const requestMethod = editUserForm.attributes['method']['value'];
    data = formDataToJson(data);

    createEditRequest(data, requestMethod, requestUrl);
  });

}

function createEditRequest(data, requestMethod, requestUrl) {
  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      headers: {
        'Content-Type': 'application/json'
      },
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
    alert('user edited successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(editUserFormMessagesContainer, resData['message'], resData['success'], true);
  }
}