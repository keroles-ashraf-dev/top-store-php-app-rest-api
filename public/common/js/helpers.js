import * as storage from "../../common/js/storage.js"

function log(message) {
  console.log(message);
}

function delay(fun, timeOut = 5000) {
  setTimeout(fun, timeOut);
}

function redirectTo(url) {
  window.location.href = url;
}

function displayScreenCover(display = true) {
  const screenCover = document.getElementById("js-screen-cover");

  if (display) {
    screenCover?.classList.add("show");
  } else {
    screenCover?.classList.remove("show");
  }
}

function hideFooter() {
  document.querySelector('footer').style = 'display: none'
}

function displayContentCover(display = true) {
  const contentCover = document.getElementById("js-content-cover");

  if (display) {
    contentCover?.classList.add("show");
  } else {
    contentCover?.classList.remove("show");
  }
}

function displayLoadingScreen(display = true) {
  const screen = document.getElementById("js-loading-screen");

  if (display) {
    screen?.classList.add("show");
  } else {
    screen?.classList.remove("show");
  }
}

function isCurrentPath(path) {
  return window.location.pathname.includes(path);
}

function formDataToJson(data) {
  return JSON.stringify(Array.from(data.entries()).reduce((map = {}, [key, value]) => {
    return {
      ...map,
      [key]: map[key] ? [...map[key], value] : value,
    };
  }, {}));
}

function htmlSpecialChars_encode(str) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return str.replace(/[&<>"']/g, m => map[m]);
}

function htmlSpecialChars_decode(str) {
  const map =
  {
    '&amp;': '&',
    '&lt;': '<',
    '&gt;': '>',
    '&quot;': '"',
    '&#039;': "'"
  };
  return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, (m) => map[m]);
}

function toggleRequestResponseMessages(presentationElement, messages = '', succeed = false, show = false) {

  if (!presentationElement) return;

  presentationElement.classList.remove('show');
  presentationElement.classList.add('hide');
  presentationElement.innerHTML = messages;

  if (show && succeed) {
    presentationElement.classList.remove('hide');
    presentationElement.classList.remove('error');
    presentationElement.classList.add('show');
    presentationElement.classList.add('info');
  }
  else if (show && !succeed) {
    presentationElement.classList.remove('hide');
    presentationElement.classList.remove('info');
    presentationElement.classList.add('show');
    presentationElement.classList.add('error');
  }
}

export {
  log,
  delay,
  redirectTo,
  displayScreenCover,
  displayContentCover,
  displayLoadingScreen,
  hideFooter,
  isCurrentPath,
  formDataToJson,
  htmlSpecialChars_encode,
  htmlSpecialChars_decode,
  toggleRequestResponseMessages,
}