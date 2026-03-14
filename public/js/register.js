const form = document.getElementById("createAdminForm");

//We first check if the admin exist
fetch("/safedose/controller/adminExists.php")
  .then((r) => r.json())
  .then((data) => {
    if (data.adminExists) {
      alertAndRedirectToLogin("Admin account already registered.", "warning");
    }
  })
  .catch((err) => {
    console.error(err);
  });

const fullName = document.getElementById("name");
const email = document.getElementById("email");
const phoneNumber = document.getElementById("phoneNumber");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmPassword");
const createAdminSubmitBtn = form.querySelector("button[type='submit']");
const createAdminError = document.getElementById("createAdminError");

fullName.addEventListener("blur", function () {
  validateName(fullName, "nameError");
});

email.addEventListener("blur", function () {
  validateEmail(email, "emailError");
});

phoneNumber.addEventListener("blur", function () {
  validatePhoneNumber(phoneNumber, "phoneError");
});

password.addEventListener("blur", function () {
  validatePassword(password, "passwordError");
});

confirmPassword.addEventListener("blur", function () {
  validatePasswordMatch(password, confirmPassword, "confirmPasswordError");
});

form.addEventListener("submit", function (e) {
  e.preventDefault();

  var isNameValid = validateName(fullName, "nameError");
  var isEmailValid = validateEmail(email, "emailError");
  var isPhoneNumberValid = validatePhoneNumber(phoneNumber, "phoneError");
  var isPasswordValid = validatePassword(password, "passwordError");
  var isMatch = validatePasswordMatch(
    password,
    confirmPassword,
    "confirmPasswordError",
  );

  if (
    !isNameValid ||
    !isEmailValid ||
    !isPhoneNumberValid ||
    !isPasswordValid ||
    !isMatch
  ) {
    return;
  }

  createAdminSubmitBtn.disabled = true;
  createAdminSubmitBtn.textContent = "Creating account…";
  createAdminError.classList.add("d-none");

  let formData = new FormData();
  formData.append("name", fullName.value);
  formData.append("email", email.value);
  formData.append("phoneNumber", phoneNumber.value);
  formData.append("password", password.value);

  fetch("/safedose/controller/auth/register.php", {
    method: "POST",
    body: formData,
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        alertAndRedirectToLogin(data.message, "success");
      } else {
        createAdminSubmitBtn.disabled = false;
        createAdminSubmitBtn.textContent = "Create Admin Account";
        createAdminError.textContent = data.message;
        createAdminError.classList.remove("d-none");
      }
    })
    .catch((err) => {
      console.error(err);
      createAdminSubmitBtn.disabled = false;
      createAdminSubmitBtn.textContent = "Create Admin Account";
      createAdminError.textContent = "Something went wrong. Please try again.";
      createAdminError.classList.remove("d-none");
    });
});

function alertAndRedirectToLogin(message, type = "warning") {
  form.style.display = "none";
  form.previousElementSibling.style.display = "none";
  const msg = document.createElement("div");
  msg.className = `alert alert-${type} text-center mt-3`;
  msg.textContent = message + " Redirecting to login...";
  form.parentElement.appendChild(msg);
  setTimeout(() => {
    window.location.href = "/safedose/auth/login.php";
  }, 2000);
}
