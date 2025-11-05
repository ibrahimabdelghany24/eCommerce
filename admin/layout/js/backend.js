const eyeIcon = document.querySelector("i.eye")
const inputs = document.querySelectorAll(".form-control");
const confirm = document.querySelectorAll(".confirm")
const viewLess = document.querySelector(".v-less");
const viewFull = document.querySelector(".v-full");
const categorey = document.querySelectorAll(".cat");

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
  confirm.forEach((e) => {
    e.addEventListener("click", (event) => {
      if (!window.confirm("Are You Sure?")){
        event.preventDefault();
      }
    })
  })
}

categorey.forEach((cat) => {
  cat.addEventListener("click", () => {
    cat.lastElementChild.classList.toggle("hide-details")
    viewFull.lastElementChild.classList.add("hidden")
    viewLess.lastElementChild.classList.add("hidden")
  })
});

viewLess.onclick = () => {
  viewLess.lastElementChild.classList.remove("hidden");
  viewFull.lastElementChild.classList.add("hidden");
  const details = document.querySelectorAll(".cat-details");
  details.forEach(element => {
    element.classList.add("hide-details");
  });
}

viewFull.onclick = () => {
  viewFull.lastElementChild.classList.remove("hidden");
  viewLess.lastElementChild.classList.add("hidden");
  const details = document.querySelectorAll(".cat-details");
  details.forEach(element => {
    element.classList.remove("hide-details")
  });
}