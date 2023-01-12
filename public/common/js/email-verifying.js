
import { log, formDataToJson, toggleRequestResponseMessages } from "./helpers.js"

// ************************** global vars *************************************//

const messagesContainer = document.getElementById("js-response-container");

// ************************** functions call *************************************//

handleEmailVerifyingData();  // handle email verifying form submit
handleResendOTPClick();  // handle resend otp button on click and send request for new otp

// ************************** functions deceleration *************************************//

function handleEmailVerifyingData() {

  toggleRequestResponseMessages(messagesContainer);

  const emailVerifyingForm = document.getElementById("js-email-verifying-form");

  emailVerifyingForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    let data = new FormData(emailVerifyingForm);

    const requestUrl = emailVerifyingForm.attributes['action']['value'];
    const requestMethod = emailVerifyingForm.attributes['method']['value'];
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

    sendEmailVerifyingData(request);
  })
}

function sendEmailVerifyingData(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleEmailVerifyingResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleEmailVerifyingResponse(resData) {
  if (resData['redirect-to']) {
    setTimeout(() => {
      window.location.href = resData['redirect-to'];
      return;
    }, 3000);
  }

  if (resData['message']) {
    toggleRequestResponseMessages(messagesContainer, resData['message'], resData['success'], true);
  }

}


function handleResendOTPClick() {

  toggleRequestResponseMessages(messagesContainer);

  const resendOtpBtn = document.getElementById("js-resend-otp");

  resendOtpBtn?.addEventListener("click", (e) => {
    e.preventDefault();

    const requestUrl = resendOtpBtn.dataset.url;
    const requestMethod = 'GET';

    const request = new Request(
      requestUrl,
      {
        method: requestMethod,
      });

    sendAskingOtpRequest(request);
  })

}

function sendAskingOtpRequest(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleResendOtpResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleResendOtpResponse(resData) {
  if (resData['message']) {
    toggleRequestResponseMessages(messagesContainer, resData['message'], resData['success'], true);
  }
}