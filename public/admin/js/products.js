import { log, delay, displayScreenCover, formDataToJson, htmlSpecialChars_decode, toggleRequestResponseMessages } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const productsMessagesContainer = document.getElementById("js-response-container");
const editProductModel = document.getElementById('js-edit-product-model-container');
const addProductModel = document.getElementById('js-add-product-model-container');
let editProductFormMessagesContainer;
let addProductFormMessagesContainer;

// ************************** functions call *************************************//

handleDeleteProduct();  // query delete buttons and send delete request
handleProductChangeStatus();  // query change status buttons and send change status request
handleEditFormDisplay();  // query edit product buttons and send request to show edit form
handleAddFormDisplay();  // query edit product buttons and send request to show edit form

// ************************** functions deceleration *************************************//

function handleDeleteProduct() {

  const deleteButtons = Array.from(document.getElementsByClassName("js-products-delete"));

  deleteButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(productsMessagesContainer);

      const productId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("delete: " + productId);

      if (action) createDeleteRequest(productId, url);
    });
  });
}

function createDeleteRequest(productId, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': productId };

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
    document.getElementById(resData['productId']).outerHTML = "";
  }

  if (resData['message']) {
    toggleRequestResponseMessages(productsMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(productsMessagesContainer));
  }

}

function handleProductChangeStatus() {

  const changeStatusButtons = Array.from(document.getElementsByClassName("js-products-status"));

  changeStatusButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(productsMessagesContainer);

      const productId = e?.dataset.id;
      const url = e?.dataset.url;
      const row = document.getElementById(productId);
      const statusField = row.querySelector('#js-product-status-field');
      const productStatus = statusField.textContent == 'enabled' ? 0 : 1;

      const action = confirm("change status: " + productId);

      if (action) createChangeStatusRequest(productId, productStatus, url);
    });
  });
}

function createChangeStatusRequest(productId, productStatus, url) {

  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': productId, 'status': productStatus };

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
    const parent = document.getElementById(resData['productId']);
    const statusBtn = parent.querySelector('.js-products-status');
    const statusField = parent.querySelector('#js-product-status-field');
    statusBtn.innerHTML = status == 'enabled' ? '<i class="fa-solid fa-ban">' : '<i class="fa-solid fa-circle-check">';
    statusField.textContent = status;
  }

  if (resData['message']) {
    toggleRequestResponseMessages(productsMessagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(productsMessagesContainer));
  }
}

function handleEditFormDisplay() {

  const editButtons = Array.from(document.getElementsByClassName("js-products-edit"));

  editButtons.forEach(e => {

    e?.addEventListener('click', _ => {

      toggleRequestResponseMessages(productsMessagesContainer);

      const productId = e?.dataset.id;
      const url = e?.dataset.url;

      const action = confirm("edit: " + productId);

      if (action) createEditFormDisplayRequest(productId, url);

    });
  });
}

function createEditFormDisplayRequest(productId, url) {
  const requestUrl = url;
  const requestMethod = 'POST';
  const data = { 'id': productId };

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
    editProductModel.innerHTML += htmlSpecialChars_decode(resData['data']);

    //init edit product model and form
    initAndHandleEditProductFormActions();

    displayScreenCover();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(productsMessagesContainer, resData['message'], resData['success'], true);
  }
}

function initAndHandleEditProductFormActions() {

  editProductFormMessagesContainer = editProductModel.querySelector('#js-edit-form-response-container');
  const editProductForm = editProductModel.querySelector('#js-edit-product-form');
  const editProductImagesInput = editProductModel.querySelector('#js-edit-product-form-image-input');
  const editProductImagesContainer = editProductModel.querySelector('#js-images-container');
  const cancelButton = editProductModel.querySelector('#js-edit-form-cancel');

  // hide edit product model
  cancelButton?.addEventListener('click', (e) => {
    e.preventDefault();

    editProductModel.innerHTML = "";

    displayScreenCover(false);
  });

  // listen to image pickup
  editProductImagesInput?.addEventListener('change', (e) => {

    editProductImagesContainer.innerHTML = '';

    const files = Array.from(editProductImagesInput.files);

    if (files.length > 5) {
      files.length = 5;
    }

    files.forEach(file => {

      const element = document.createElement('img');
      element.src = URL.createObjectURL(file);
      editProductImagesContainer.appendChild(element);
    });
  });

  editProductForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(editProductFormMessagesContainer);

    const data = new FormData(editProductForm);
    data.delete('product-images');

    let filesLength = editProductImagesInput.files.length;

    if (filesLength > 5) filesLength = 5;

    for (let i = 0; i < filesLength; i++) {
      data.append('product-image-' + i, editProductImagesInput.files[i]);
    }

    const requestUrl = editProductForm.attributes['action']['value'];
    const requestMethod = editProductForm.attributes['method']['value'];

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
    alert('product edited successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(editProductFormMessagesContainer, resData['message'], resData['success'], true);
  }
}

function handleAddFormDisplay() {

  const addButton = document.getElementById("js-products-add");

  addButton?.addEventListener('click', _ => {

    toggleRequestResponseMessages(productsMessagesContainer);

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
    addProductModel.innerHTML += htmlSpecialChars_decode(resData['data']);

    //init add product model and form
    initAndHandleAddProductFormActions();

    displayScreenCover();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(productsMessagesContainer, resData['message'], resData['success'], true);
  }
}

function initAndHandleAddProductFormActions() {

  addProductFormMessagesContainer = addProductModel.querySelector('#js-add-form-response-container');
  const addProductForm = addProductModel.querySelector('#js-add-product-form');
  const addProductImageInput = addProductModel.querySelector('#js-add-product-form-image-input');
  const addProductImagesContainer = addProductModel.querySelector('#js-images-container');
  const cancelButton = addProductModel.querySelector('#js-add-form-cancel');

  // hide add product model
  cancelButton?.addEventListener('click', (e) => {
    e.preventDefault();

    addProductModel.innerHTML = "";

    displayScreenCover(false);
  });

  // listen to image pickup
  addProductImageInput?.addEventListener('change', (e) => {

    addProductImagesContainer.innerHTML = '';

    const files = Array.from(addProductImageInput.files);

    if (files.length > 5) {
      files.length = 5;
    }

    files.forEach(file => {

      const element = document.createElement('img');
      element.src = URL.createObjectURL(file);
      addProductImagesContainer.appendChild(element);
    });
  });

  addProductForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(addProductFormMessagesContainer);

    const data = new FormData(addProductForm);
    data.delete('product-images');

    let filesLength = addProductImageInput.files.length;

    if (filesLength > 5) filesLength = 5;

    for (let i = 0; i < filesLength; i++) {
      data.append('product-image-' + i, addProductImageInput.files[i]);
    }

    const requestUrl = addProductForm.attributes['action']['value'];
    const requestMethod = addProductForm.attributes['method']['value'];

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
    alert('product created successfully');
    location.reload();
  }

  if (!resData['success'] && resData['message']) {
    toggleRequestResponseMessages(addProductFormMessagesContainer, resData['message'], resData['success'], true);
  }
}