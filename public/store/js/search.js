import { log, displayLoadingScreen, redirectTo } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const responseMessagesContainer = document.getElementById('js-response-container');

// ************************** functions call *************************************//

setProductRating(); // set rating view for product

// ************************** functions deceleration *************************************//

function setProductRating() {

  const ratingContainers = Array.from(document.getElementsByClassName('js-rating-container'));

  ratingContainers?.forEach(e => {

    const rating = e?.dataset.rating;
    const ratingInt = Number(rating.split('.')[0]);
    const ratingFraction = Number(rating.split('.')[1]);
    let fractionAdded = false;

    for (let i = 1; i <= 5; i++) {

      if (i <= ratingInt) {
        const star = '<i class="fa-solid fa-star"></i>';
        e.innerHTML += star;
        continue;
      }

      if (ratingFraction > 0 && !fractionAdded) {
        fractionAdded = true;
        const star = '<i class="fa-regular fa-star-half-stroke"></i>';
        e.innerHTML += star;
        continue;
      }

      const star = '<i class="fa-regular fa-star"></i>';
      e.innerHTML += star;
    }
  });
}