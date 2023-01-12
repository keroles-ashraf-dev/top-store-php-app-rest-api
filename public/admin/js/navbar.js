import { log } from "../../common/js/helpers.js"
import { language } from "../../common/js/i18n.js"

// ************************** functions call *************************************//

initLanguageLabel();  // init nav language label

initLanguageRadiosValue();  // init nav language radios

//handleNavSettingsPopupMenuDisplaying();  // display content cover on settings popup menu appear

//handleNavAccountPopupMenuDisplaying();  // display content cover on account popup menu appear

// ************************** functions deceleration *************************************//

function initLanguageLabel() {

  const navLanguageLabel = document.getElementById('js-nav-settings-box-label'); // language display element

  if (navLanguageLabel)
    navLanguageLabel.textContent = language().toUpperCase();
}

function initLanguageRadiosValue() {

  const radios = document.querySelectorAll('input[type=radio][name="language-option"]'); // language options elements

  if (radios === null) return;

  for (let radio of radios) {

    if (radio.getAttribute('value') === language()) {
      radio.checked = true;
      break;
    }
  }
}

// function handleNavSettingsPopupMenuDisplaying() {

//   const settingsBtn = document.getElementById("js-nav-settings-box")
//   const bodyCover = document.getElementById("js-content-cover")

//   settingsBtn?.addEventListener("mouseenter", () => {
//     bodyCover.classList.add("show");
//   });

//   settingsBtn?.addEventListener("mouseleave", () => {
//     bodyCover.classList.remove("show");
//   });
// }

// function handleNavAccountPopupMenuDisplaying() {

//   const accountBtn = document.getElementById("js-nav-account-box")
//   const bodyCover = document.getElementById("js-content-cover")

//   accountBtn?.addEventListener("mouseenter", () => {
//     bodyCover.classList.add("show");
//   });

//   accountBtn?.addEventListener("mouseleave", () => {
//     bodyCover.classList.remove("show");
//   });
// }