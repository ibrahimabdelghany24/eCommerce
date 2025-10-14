let eyeIcon = document.querySelector("i.eye")
let inputs = document.querySelectorAll(".form-control");

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

let required_input = document.querySelectorAll("input[required]");
required_input.forEach(($e) => {
  let span = document.createElement("span");
  span.classList.add("astrisk")
  span.innerHTML = "*";
  console.log(span)
  $e.insertAdjacentElement("afterend", span)
})