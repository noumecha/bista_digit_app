let tBtn = document.getElementById("toast-btn");
let tBlock = document.getElementById("toast-block");
let tClose = document.getElementById("toast-close");

tBtn.addEventListener('click', function() {
  tBlock.style.display = "flex";

  setTimeout(() => {
    tBlock.style.display = "none";
  }, 4000);
});

tClose.addEventListener('click', function() {
  tBlock.style.display = "none";
});
