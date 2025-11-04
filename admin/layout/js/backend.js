let eyeIcon = document.querySelector("i.eye")
let inputs = document.querySelectorAll(".form-control");
let confirm = document.querySelectorAll(".confirm")
let viewLess = document.querySelector(".v-less");
let viewFull = document.querySelector(".v-full");
let catView = localStorage.getItem("view");

if (eyeIcon) {
  eyeIcon.addEventListener("click", (e) => {
    if (e.target.classList.contains("fa-eye")) {
      e.target.classList.remove("fa-eye");
      e.target.classList.add("fa-eye-slash");
      e.target.style.color = "#757575";
      inputs[1].type = "password";
    } else {
      e.target.classList.add("fa-eye");
      e.target.classList.remove("fa-eye-slash");
      e.target.style.color = "#008dde";
      inputs[1].type = "text";
    }
  })
}

if (inputs) {
  inputs.forEach((input) => {
    input.addEventListener("focus", (e) => {
      e.target.placeholder = "";
    })
  })

  inputs.forEach((input) => {
    input.addEventListener("blur", (e) => {
      e.target.placeholder = e.target.dataset.text;
    })
  })
}
if (confirm) {
  confirm.forEach(($e) => {
    $e.addEventListener("click", ($event) => {
      if (!window.confirm("Are You Sure?")){
        $event.preventDefault();
      }
    })
  })
}

viewLess.addEventListener("click", () => {
  if (!viewLess.classList.contains("active")) {
    localStorage.setItem("view", "less");
    viewFull.classList.remove("active");
    viewLess.classList.add("active");
    viewLess.lastElementChild.classList.remove("hidden");
    viewFull.lastElementChild.classList.add("hidden");
    const details = document.querySelectorAll(".cat-details");
    details.forEach(element => {
      element.classList.add("hidden");
    });
  }
});
viewFull.addEventListener("click", () => {
  if (!viewFull.classList.contains("active")) {
    localStorage.setItem("view", "full");
    viewLess.classList.remove("active");
    viewFull.classList.add("active");
    viewFull.lastElementChild.classList.remove("hidden");
    viewLess.lastElementChild.classList.add("hidden");
    const details = document.querySelectorAll(".cat-details");
    details.forEach(element => {
      element.classList.remove("hidden")
    });
  }
});
if (catView && catView == "less") {
  viewLess.click();
}