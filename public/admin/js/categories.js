import { log, delay, displayScreenCover, formDataToJson, htmlSpecialChars_decode, toggleRequestResponseMessages } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const categoriesMessagesContainer = document.getElementById("js-response-container");
const editCategoryModel = document.getElementById('js-edit-category-model-container');
const addCategoryModel = document.getElementById('js-add-category-model-container');
let editCategoryFormMessagesContainer;
let addCategoryFormMessagesContainer;

// ************************** functions call *************************************//

handleDeleteCategory();  // query delete buttons and send delete request
handleUserChangeStatus();  // query change status buttons and send change status request
handleEditFormDisplay();  // query edit category buttons and send request to show edit form
handleAddFormDisplay();  // query edit category buttons and send request to show edit form

// ************************** functions deceleration *************************************//

function handleDeleteCategory() {

  const deleteButtons = Array.from(document.getElementsByClassName("js-categories-delete"));

  deleteButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(categoriesMessagesContainer);

      const categoryId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("delete: " + categoryId);

      if (action) createDeleteRequest(categoryId, url);
    });
  });
}

function createDeleteRequest(categoryId, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': categoryId };

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
    document.getElementById(resData['categoryId']).outerHTML = "";
  }

  if (resData['message']) {
    toggleRequestResponseMessages(categoriesMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(categoriesMessagesContainer));
  }

}

function handleUserChangeStatus() {

  const changeStatusButtons = Array.from(document.getElementsByClassName("js-categories-status"));

  changeStatusButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(categoriesMessagesContainer);

      const categoryId = e?.dataset.id;
      const url = e?.dataset.url;
      const row = document.getElementById(categoryId);
      const statusField = row.querySelector('#js-category-status-field');
      const categoryStatus = statusField.textContent == 'enabled' ? 0 : 1;

      const action = confirm("change status: " + categoryId);

      if (action) createChangeStatusRequest(categoryId, categoryStatus, url);
    });
  });
}

function createChangeStatusRequest(categoryId, categoryStatus, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': categoryId, 'status': categoryStatus };

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
    const parent = document.getElementById(resData['categoryId']);
    const statusBtn = parent.querySelector('.js-categories-status');
    const statusField = parent.querySelector('#js-category-status-field');
    statusBtn.innerHTML = status == 'enabled' ? '<i class="fa-solid fa-ban">' : '<i class="fa-solid fa-circle-check">';
    statusField.textContent = status;
  }

  if (resData['message']) {
    toggleRequestResponseMessages(categoriesMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(categoriesMessagesContainer));
  }
}

function handleEditFormDisplay() {

  const editButtons = Array.from(document.getElementsByClassName("js-categories-edit"));

  editButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(categoriesMessagesContainer);

      const categoryId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("edit: " + categoryId);

      if (action) createEditFormDisplayRequest(categoryId, url);

    });
  });
}

function createEditFormDisplayRequest(categoryId, url) {
  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': categoryId };

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
    editCategoryModel.innerHTML += htmlSpecialChars_decode(resData['data']);

    //init edit category model and form
    initAndHandleEditCategoryFormActions();

    displayScreenCover();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(categoriesMessagesContainer, resData['message'], resData['success'], true);
  }
}

function initAndHandleEditCategoryFormActions() {

  editCategoryFormMessagesContainer = editCategoryModel.querySelector('#js-edit-form-response-container');
  const editCategoryForm = editCategoryModel.querySelector('#js-edit-category-form');
  const editCategoryImageInput = editCategoryModel.querySelector('#js-edit-category-form-image-input');
  const editCategoryImage = editCategoryModel.querySelector('#js-edit-category-form-image');
  const cancelButton = editCategoryModel.querySelector('#js-edit-form-cancel');

  // hide edit category model
  cancelButton?.addEventListener('click', (e) => {
    e.preventDefault();

    editCategoryModel.innerHTML = "";

    displayScreenCover(false);
  });

  // listen to image pickup
  editCategoryImageInput?.addEventListener('change', (e) => {

    const file = editCategoryImageInput.files[0];
    editCategoryImage.src = URL.createObjectURL(file);
  });

  editCategoryForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(editCategoryFormMessagesContainer);

    let data = new FormData(editCategoryForm);

    const requestUrl = editCategoryForm.attributes['action']['value'];
    const requestMethod = editCategoryForm.attributes['method']['value'];

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
    alert('category edited successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(editCategoryFormMessagesContainer, resData['message'], resData['success'], true);
  }
}

function handleAddFormDisplay() {

  const addButton = document.getElementById("js-categories-add");

  addButton?.addEventListener('click', _ => {

    toggleRequestResponseMessages(categoriesMessagesContainer);

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
    addCategoryModel.innerHTML += htmlSpecialChars_decode(resData['data']);

    //init add category model and form
    initAndHandleAddCategoryFormActions();

    displayScreenCover();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(categoriesMessagesContainer, resData['message'], resData['success'], true);
  }
}

function initAndHandleAddCategoryFormActions() {

  addCategoryFormMessagesContainer = addCategoryModel.querySelector('#js-add-form-response-container');
  const addCategoryForm = addCategoryModel.querySelector('#js-add-category-form');
  const addCategoryImageInput = addCategoryModel.querySelector('#js-add-category-form-image-input');
  const addCategoryImage = addCategoryModel.querySelector('#js-add-category-form-image');
  const cancelButton = addCategoryModel.querySelector('#js-add-form-cancel');

  // hide add category model
  cancelButton?.addEventListener('click', (e) => {
    e.preventDefault();

    addCategoryModel.innerHTML = "";

    displayScreenCover(false);
  });

  // listen to image pickup
  addCategoryImageInput?.addEventListener('change', (e) => {

    const file = addCategoryImageInput.files[0];
    addCategoryImage.src = URL.createObjectURL(file);
  });

  addCategoryForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(addCategoryFormMessagesContainer);

    const data = new FormData(addCategoryForm);

    const requestUrl = addCategoryForm.attributes['action']['value'];
    const requestMethod = addCategoryForm.attributes['method']['value'];

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
    alert('category created successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(addCategoryFormMessagesContainer, resData['message'], resData['success'], true);
  }
}