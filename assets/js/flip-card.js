const cards = document.querySelectorAll(".item-card-inner");

function flipCard(event) {
  // ignore clicks on interactive elements or anything with .no-flip
  if (event.target.closest('a, button, input, textarea, select, label, .no-flip')) {
    return;
  }
  this.classList.toggle("is-flipped");
}
cards.forEach((card) => card.addEventListener("click", flipCard));