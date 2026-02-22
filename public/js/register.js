const form = document.getElementById("createAdminForm");

const fullName = document.getElementById("name");
const email = document.getElementById("email");
const phoneNumber = document.getElementById("phoneNumber");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmPassword");

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

  let formData = new FormData();
  formData.append("name", fullName.value);
  formData.append("email", email.value);
  formData.append("phoneNumber", phoneNumber.value);
  formData.append("password", password.value);

  console.log(formData);

  fetch("/safedose/controller/register.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.href = "/safedose/auth/login.php";
      }
      console.log(data);
    })
    .catch((err) => console.error(err));
});
