import { log, toggleRequestResponseMessages, delay, redirectTo } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const responseMessagesContainer = document.getElementById('js-response-container');

// ************************** functions call *************************************//

handleEditUserName(); // handle edit user name request
handleEditUserEmail(); // handle edit user email request
handleSendUserEmailOTP(); // handle send email otp request
handleEditUserPhone(); // handle edit user phone request
handleEditUserPassword(); // handle edit user password request
displayCountryCodeSelect();  // display country code of select in brief(US +1) format 

// ************************** functions deceleration *************************************//

function handleEditUserName() {

  const form = document.getElementById('js-profile-edit-name-form');

  form?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(responseMessagesContainer);

    const data = new FormData(form);
    const requestUrl = form.attributes['action']['value'];
    const requestMethod = form.attributes['method']['value'];

    createPostRequest(data, requestMethod, requestUrl);
  });
}

function handleEditUserEmail() {

  const form = document.getElementById('js-profile-edit-email-form');

  form?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(responseMessagesContainer);

    const data = new FormData(form);
    const requestUrl = form.attributes['action']['value'];
    const requestMethod = form.attributes['method']['value'];

    createPostRequest(data, requestMethod, requestUrl);
  });
}

function handleSendUserEmailOTP() {

  const form = document.getElementById('js-profile-verify-email-otp-form');

  form?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(responseMessagesContainer);

    const data = new FormData(form);
    const requestUrl = form.attributes['action']['value'];
    const requestMethod = form.attributes['method']['value'];

    createPostRequest(data, requestMethod, requestUrl);
  });
}

function handleEditUserPhone() {

  const form = document.getElementById('js-profile-edit-phone-form');

  form?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(responseMessagesContainer);

    let data = new FormData(form);
    data = handlePhoneNumberAndReturnData(data);

    const requestUrl = form.attributes['action']['value'];
    const requestMethod = form.attributes['method']['value'];

    createPostRequest(data, requestMethod, requestUrl);
  });
}

function handlePhoneNumberAndReturnData(data) {
  const code = data.get("code");
  let phone = data.get("phone");
  phone = phone.replaceAll(/\s+/g, "");
  data.set("phone", "+" + code + phone);
  data.delete("code");
  return data;
}

function handleEditUserPassword() {

  const form = document.getElementById('js-profile-edit-password-form');

  form?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(responseMessagesContainer);

    const data = new FormData(form);
    const requestUrl = form.attributes['action']['value'];
    const requestMethod = form.attributes['method']['value'];

    createPostRequest(data, requestMethod, requestUrl);
  });
}

function createPostRequest(data, requestMethod, requestUrl) {
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

  if (resData['message']) {
    toggleRequestResponseMessages(responseMessagesContainer, resData['message'], resData['success'], true);
    if (resData['success']) delay(() => toggleRequestResponseMessages(responseMessagesContainer));
  }
}

function displayCountryCodeSelect() {

  const countrySelect = document.getElementById("js-country-code-select");
  const countryCodeDisplay = document.getElementById("js-country-code-display");

  countrySelect?.addEventListener("change", () => {

    const selectedOption = countrySelect.options[countrySelect.selectedIndex];
    const code = selectedOption.dataset.code
    countryCodeDisplay.innerText = code;
  });
}
