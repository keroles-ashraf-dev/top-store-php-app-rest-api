import { log, displayContentCover } from "../../common/js/helpers.js"
import { language } from "../../common/js/i18n.js"

// ************************** functions call *************************************//

displayNavCategoriesSelect();  // display categories of select

handleSearchBarBorderOnFocus();  // display border on search bar focus

initLanguageLabel();  // init nav language label

initLanguageRadiosValue();  // init nav language radios

handleNavPopupMenusDisplaying();  // display content cover on popup menu appear

handleHamburgerMenuDisplaying();  // display hamburger menu and screen cover

hideScreenCoverOnHamburgerMenuDismissed();  // hide screen cover on hamburger menu hidden

// ************************** functions deceleration *************************************//

function displayNavCategoriesSelect() {

  const categoriesSelect = document.getElementById("js-categories-select");
  const categoriesDisplay = document.getElementById("js-categories-select-display");

  const selectedOption = categoriesSelect?.options[categoriesSelect.selectedIndex];

  if(categoriesDisplay !== null){
    categoriesDisplay.innerText = selectedOption.dataset.name;
  }

  categoriesSelect?.addEventListener("change", () => {

    const selectedOption = categoriesSelect.options[categoriesSelect.selectedIndex];
    categoriesDisplay.innerText = selectedOption.dataset.name;
  });
}

function handleSearchBarBorderOnFocus() {

  const inputSearch = document.getElementById("js-nav-search-input");
  const searchContainer = document.getElementById("js-nav-search-container");
  const bodyCover = document.getElementById("js-content-cover")

  inputSearch?.addEventListener('focusin', () => {

    searchContainer.classList.add("search-bar-focus");
    bodyCover.classList.add("show");

  }, true);

  inputSearch?.addEventListener('focusout', () => {

    searchContainer.classList.remove("search-bar-focus");
    bodyCover.classList.remove("show");

  }, true);
}

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

function handleNavPopupMenusDisplaying() {

  const elements = [
    document.getElementById("js-nav-settings-box"),
    document.getElementById("js-nav-account-box")
  ];

  elements.forEach(e => {

    e?.addEventListener("mouseenter", () => {
      displayContentCover();
    });

    e?.addEventListener("mouseleave", () => {
      displayContentCover(false);
    });
  });
}

function handleHamburgerMenuDisplaying() {

  const menuBtn = document.getElementById("js-hamburger-menu-btn")
  const menuContent = document.getElementById("js-hamburger-menu-content")
  const screenCover = document.getElementById("js-screen-cover")

  menuBtn?.addEventListener("click", () => {

    menuContent.classList.add("hamburger-menu-show");
    screenCover.classList.add("show");
  });
}

function hideScreenCoverOnHamburgerMenuDismissed() {

  window.addEventListener('mouseup', function (e) {

    const menuContent = document.getElementById("js-hamburger-menu-content")
    const screenCover = document.getElementById("js-screen-cover")

    if (menuContent && menuContent !== e.target) {
      menuContent.classList.remove("hamburger-menu-show");
      screenCover.classList.remove("show");
    }
  });
}