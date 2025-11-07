const cards = document.querySelectorAll(".item-card-inner");

function flipCard() {
  this.classList.toggle("is-flipped");
}
cards.forEach((card) => card.addEventListener("click", flipCard));