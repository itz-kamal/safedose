const userData = JSON.parse(localStorage.getItem("safedoseUser") || "null");

if (!userData) {
  window.location.href = "/safedose/auth/login.php";
} else {
  window.currentUser = userData;
}

function handleAuthError(data) {
  if (
    !data.success &&
    (data.message === "Invalid or expired token" || data.message === "User account is inactive")
  ) {
    localStorage.removeItem("safedoseUser");
    window.location.href = "/safedose/auth/login.php";
    return true;
  }
  return false;
}

document.addEventListener("DOMContentLoaded", function () {
  const greeting = document.getElementById("greeting");
  if (greeting) {
    greeting.textContent = "Welcome, " + window.currentUser.name;
  }
});
