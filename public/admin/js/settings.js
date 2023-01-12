import { log, delay, displayScreenCover, formDataToJson, htmlSpecialChars_decode, toggleRequestResponseMessages } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const messagesContainer = document.getElementById("js-response-container");
const settingsForm = document.getElementById("js-settings-form");

// ************************** functions call *************************************//

saveSettingsFormSubmit();  // listen to settings save form submit

// ************************** functions deceleration *************************************//

function saveSettingsFormSubmit() {

  settingsForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    toggleRequestResponseMessages(messagesContainer);

    const data = new FormData(settingsForm);

    const requestUrl = settingsForm.attributes['action']['value'];
    const requestMethod = settingsForm.attributes['method']['value'];

    const action = confirm('save settings');

    if (action) createSaveRequest(data, requestMethod, requestUrl);
  });

}

function createSaveRequest(data, requestMethod, requestUrl) {
  const request = new Request(
    requestUrl,
    {
      method: requestMethod,
      body: data,
    });

  sendSaveRequest(request);
}

function sendSaveRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleSaveResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleSaveResponse(resData) {

  if (resData['message']) {
    toggleRequestResponseMessages(messagesContainer, resData['message'], resData['success'], true);
    delay(() => toggleRequestResponseMessages(messagesContainer));
  }
}