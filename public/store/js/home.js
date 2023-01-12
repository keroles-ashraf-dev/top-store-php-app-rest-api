import { log } from "../../common/js/helpers.js"
import { language, isRTL } from "../../common/js/i18n.js"

// ************************** global vars *************************************//

const SLIDER_INTERVAL = 3000;
let mSliderIndex = 1; // current slider index
let mSliderTimer;

// ************************** functions call *************************************//

setHomeSlider(mSliderIndex);  // set the selected slider item

setSliderArrows();  // set previous and next buttons of slider

setDealsProductsRating(); // set rating view

// ************************** functions deceleration *************************************//

function setHomeSlider(n) {

  const slides = document.querySelectorAll(".home .slider .item");

  if (n > slides?.length) { mSliderIndex = 1 }
  if (n < 1) { mSliderIndex = slides?.length }

  for (let i = 0; i < slides?.length; i++) {
    slides[i]?.classList.add('hide');
  }

  slides[mSliderIndex - 1]?.classList.remove('hide');
  slides[mSliderIndex - 1]?.classList.add('show');

  clearTimeout(mSliderTimer);
  mSliderTimer = setTimeout(() => setHomeSlider(mSliderIndex += 1), SLIDER_INTERVAL);
}

function setSliderArrows() {

  const prev = document.getElementById('js-home-slider-prev');
  const next = document.getElementById('js-home-slider-next');

  if (isRTL()) {
    prev?.classList.add('fa-flip-horizontal');
    next?.classList.add('fa-flip-horizontal');
  }

  prev?.addEventListener('click', () => setHomeSlider(mSliderIndex += -1));
  next?.addEventListener('click', () => setHomeSlider(mSliderIndex += 1));
}

function setDealsProductsRating() {

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