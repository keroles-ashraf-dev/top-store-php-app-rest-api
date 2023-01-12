import { log } from "../../common/js/helpers.js"
import { language, isRTL } from "../../common/js/i18n.js"

// ************************** global vars *************************************//

// ************************** functions call *************************************//

setBackToTop();  // set back to top of screen btn

// ************************** functions deceleration *************************************//

function setBackToTop() {

  const backToTopBtn = document.getElementById('js-back-to-top-btn');

  backToTopBtn?.addEventListener('click', () => {

    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  });
}