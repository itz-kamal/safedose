const userData = JSON.parse(localStorage.getItem("safedoseUser") || "null");

if (!userData) {
  window.location.href = "/safedose/auth/login.php";
} else if (new Date(userData.expiresAt) < new Date()) {
  localStorage.removeItem("safedoseUser");
  window.location.href = "/safedose/auth/login.php";
} else {
  window.currentUser = userData;
}

document.addEventListener("DOMContentLoaded", function () {
  const greeting = document.getElementById("greeting");
  if (greeting) {
    greeting.textContent = "Welcome, " + window.currentUser.name;
  }
});
