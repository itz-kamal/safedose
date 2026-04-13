const form = document.getElementById("staffCreateForm");
const staffName = document.getElementById("staffName");
const email = document.getElementById("email");
const phoneNumber = document.getElementById("phoneNumber");
const password = document.getElementById("password");
const submitBtn = form.querySelector("button[type='submit']");
const staffError = document.getElementById("staffError");
const staffSuccess = document.getElementById("staffSuccess");

function generatePassword() {
  const upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  const lower = "abcdefghijklmnopqrstuvwxyz";
  const numbers = "0123456789";
  const special = "!@#$%^&*";
  const all = upper + lower + numbers + special;

  let pwd = "";
  pwd += upper[Math.floor(Math.random() * upper.length)];
  pwd += numbers[Math.floor(Math.random() * numbers.length)];
  pwd += special[Math.floor(Math.random() * special.length)];

  for (let i = 0; i < 9; i++) {
    pwd += all[Math.floor(Math.random() * all.length)];
  }

  password.value = pwd
    .split("")
    .sort(() => Math.random() - 0.5)
    .join("");
}

function copyPassword() {
  navigator.clipboard.writeText(password.value).then(() => {
    const icon = document.getElementById("copyIcon");
    icon.className = "fas fa-clipboard-check";
    setTimeout(() => {
      icon.className = "fas fa-clipboard";
    }, 2000);
  });
}

staffName.addEventListener("blur", () => validateName(staffName, "nameError"));
email.addEventListener("blur", () => validateEmail(email, "emailError"));
phoneNumber.addEventListener("blur", () => validatePhoneNumber(phoneNumber, "phoneError"));

form.addEventListener("submit", (e) => {
  e.preventDefault();

  const isNameValid = validateName(staffName, "nameError");
  const isEmailValid = validateEmail(email, "emailError");
  const isPhoneValid = validatePhoneNumber(phoneNumber, "phoneError");

  if (!isNameValid || !isEmailValid || !isPhoneValid) {
    return;
  }

  submitBtn.disabled = true;
  submitBtn.textContent = "Creating account…";
  staffError.classList.add("d-none");
  staffSuccess.classList.add("d-none");

  const formData = new FormData();
  formData.append("name", staffName.value);
  formData.append("email", email.value);
  formData.append("phoneNumber", phoneNumber.value);
  formData.append("password", password.value);
  formData.append("token", window.currentUser.token);

  fetch("/safedose/controller/user/create-staff.php", {
    method: "POST",
    body: formData,
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        staffSuccess.textContent = data.message;
        staffSuccess.classList.remove("d-none");
        form.reset();
        generatePassword();
      } else {
        staffError.textContent = data.message;
        staffError.classList.remove("d-none");
      }
      submitBtn.disabled = false;
      submitBtn.textContent = "Create Staff Account";
    })
    .catch(() => {
      staffError.textContent = "Something went wrong. Please try again.";
      staffError.classList.remove("d-none");
      submitBtn.disabled = false;
      submitBtn.textContent = "Create Staff Account";
    });
});

window.generatePassword = generatePassword;
window.copyPassword = copyPassword;

generatePassword();
