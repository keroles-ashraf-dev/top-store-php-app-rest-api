import { log } from "./helpers.js"
import * as storage from "./storage.js"

// ************************** global vars *************************************//

const mTranslatableElements = document.querySelectorAll("[data-translationKey]"); // translatable page elements
const mLanguageRadios = document.querySelectorAll('input[type=radio][name="language-option"]'); // language options elements
let mLanguage; // selected language
let mTranslations; // json of selected language 
let mIsRTL; // selected language

// ************************** functions call *************************************//

await initLanguageAndTranslations(); // get language from local storage and if null set it in storage as 'en'

translate(); // translate on page opened

handleLanguageChange(); // handle user change language action

// ************************** functions deceleration *************************************//

async function initLanguageAndTranslations() {

  let language = storage.get('language');

  if (language === null) {
    language = 'en';
    storage.set('language', language);
  }

  mLanguage = language;
  mTranslations = await loadTranslationFile(mLanguage); // json of selected language 
  mIsRTL = mTranslations['dir'] === 'rtl'; // json of selected language 
}

function handleLanguageChange() {

  mLanguageRadios?.forEach(radio => {

    addEventListenerToRadio(radio, mTranslatableElements);
  });
}

function addEventListenerToRadio(radio) {

  radio?.addEventListener('change', async (e) => {

    const value = e.target.value;

    if (mLanguage === value) return;

    mTranslations = await loadTranslationFile(value)
    storage.set('language', value);
    storage.set('dir', mTranslations['dir']);
    location.reload();
  });
}

async function loadTranslationFile(language) {
  const res = await fetch(location.origin + '/top-store/public/common/i18n/' + language + '.json');
  return res?.json();
}

function translate() {

  mTranslatableElements.forEach(e => {
    const translationKey = e.getAttribute("data-translationKey");
    const translationText = mTranslations[translationKey];

    if (translationText) e.textContent = translationText;
  });

  document.dir = mTranslations['dir'];
}

function language() {
  return mLanguage;
}

function isRTL() {
  return mIsRTL;
}

export {
  language,
  isRTL,
}