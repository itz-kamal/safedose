document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("createAdminForm");

  if (!form) {
    return;
  }

  const fullName = document.getElementById("name");
  const email = document.getElementById("email");
  const phoneNumber = document.getElementById("phoneNumber");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirmPassword");

  fullName.addEventListener("blur", function () {
    validateText(fullName, "nameError");
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

    var isNameValid = validateText(fullName, "nameError");
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
    formData.append("name", fullName);
    formData.append("email", email);
    formData.append("phoneNumber", phoneNumber);
    formData.append("password", password);

    fetch("./controller/register.php", {
      method: "POST",
      body: JSON.stringify(formData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log(data);
        } else {
          console.log("Error");
        }
      });
  });
});
