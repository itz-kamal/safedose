const existingUser = JSON.parse(localStorage.getItem("safedoseUser") || "null");
if (existingUser && new Date(existingUser.expiresAt) > new Date()) {
  window.location.href = "/safedose/dashboard/index.php";
}

const form = document.getElementById("loginForm");
const email = document.getElementById("email");
const password = document.getElementById("password");
const loginBtn = form.querySelector("button[type='submit']");
const loginError = document.getElementById("loginError");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const isEmailValid = validateEmail(email, "emailError");

  let isPasswordValid = true;
  if (password.value.length === 0) {
    setError(password, "passwordError", "Password is required");
    isPasswordValid = false;
  } else {
    setError(password, "passwordError", "");
  }

  if (!isEmailValid || !isPasswordValid) {
    return;
  }

  loginBtn.disabled = true;
  loginBtn.textContent = "Logging in…";
  loginError.classList.add("d-none");

  const formData = new FormData();
  formData.append("email", email.value);
  formData.append("password", password.value);

  fetch("/safedose/controller/login.php", { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        localStorage.setItem("safedoseUser", JSON.stringify(data.user));
        window.location.href = "/safedose/dashboard/index.php";
      } else {
        loginError.textContent = data.message;
        loginError.classList.remove("d-none");
        loginBtn.disabled = false;
        loginBtn.textContent = "Login";
      }
    })
    .catch((err) => {
      console.error(err);
      loginError.textContent = "Something went wrong. Please try again.";
      loginError.classList.remove("d-none");
      loginBtn.disabled = false;
      loginBtn.textContent = "Login";
    });
});
