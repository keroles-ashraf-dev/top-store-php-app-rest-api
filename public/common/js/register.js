
import { log, formDataToJson, toggleRequestResponseMessages } from "./helpers.js"

// ************************** global vars *************************************//

const messagesContainer = document.getElementById("js-response-container");

// ************************** functions call *************************************//

displayCountryCodeSelect();  // display country code of select in brief(US +1) format 

handleRegistrationData();  // handle registration form and send data to server

// ************************** functions deceleration *************************************//

function displayCountryCodeSelect() {

  const countrySelect = document.getElementById("js-country-code-select");
  const countryCodeDisplay = document.getElementById("js-country-code-display");

  countrySelect?.addEventListener("change", () => {

    const selectedOption = countrySelect.options[countrySelect.selectedIndex];
    const code = selectedOption.dataset.code
    countryCodeDisplay.innerText = code;
  });
}

function handleRegistrationData() {

  
  const registerForm = document.getElementById("js-register-form");
  
  registerForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    
    toggleRequestResponseMessages(messagesContainer);

    let data = new FormData(registerForm);

    data = handlePhoneNumberAndReturnData(data);

    const requestUrl = registerForm.attributes['action']['value'];
    const requestMethod = registerForm.attributes['method']['value'];
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

    sendRegistrationData(request);
  })

}

function handlePhoneNumberAndReturnData(data) {
  const code = data.get("code");
  let phone = data.get("phone");
  phone = phone.replaceAll(/\s+/g, "");
  data.set("phone", "+" + code + phone);
  data.delete("code");
  return data;
}

function sendRegistrationData(request) {
  fetch(request)
    .then(res =>
      res.json()
    ).then(resData => {
      handleRegistrationResponse(resData);
    })
    .catch(e => {
      log(e);
    });
}

function handleRegistrationResponse(resData) {
  if (resData['redirect-to']) {
    window.location.href = resData['redirect-to'];
    return;
  }

  if (resData['message']) {
    toggleRequestResponseMessages(messagesContainer, resData['message'], resData['success'], true);
  }

}