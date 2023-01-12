import { log, formDataToJson, toggleRequestResponseMessages } from "./helpers.js"

// ************************** global vars *************************************//

const messagesContainer = document.getElementById("js-response-container");

// ************************** functions call *************************************//

toggleHelpActions();  // display country code of select in brief(US +1) format 

handleLoginData();  // handle registration form and send data to server

// ************************** functions deceleration *************************************//

function toggleHelpActions() {

  const helpBoxBtn = document.getElementById("js-login-help-btn");
  const helpBoxActions = document.getElementById("js-login-help-actions");

  helpBoxBtn?.addEventListener("click", () => {
    helpBoxActions?.classList.toggle('show');
  });
}

function handleLoginData() {

  toggleRequestResponseMessages(messagesContainer);

  const loginForm = document.getElementById("js-login-form");

  loginForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    let data = new FormData(loginForm);

    const requestUrl = loginForm.attributes['action']['value'];
    const requestMethod = loginForm.attributes['method']['value'];
    data = formDataToJson(data);

    const request = new Request(
      requestUrl,
      {
        method: requestMethod,
        headers: {
          'Content-Type': 'application/json'
        },
        body: data,
      });

    sendLoginData(request);
  })

}

function sendLoginData(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleLoginResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleLoginResponse(resData) {
  if (resData['redirect-to']) {
    window.location.href = resData['redirect-to'];
    return;
  }

  if (resData['message']) {
    toggleRequestResponseMessages(messagesContainer, resData['message'], resData['success'], true);
  }

}