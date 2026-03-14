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

window.logout = function () {
  const user = JSON.parse(localStorage.getItem("safedoseUser") || "null");
  const token = user?.token;

  if (token) {
    const formData = new FormData();
    formData.append("token", token);
    fetch("/safedose/controller/auth/logout.php", {
      method: "POST",
      body: formData,
    }).finally(() => {
      localStorage.removeItem("safedoseUser");
      window.location.href = "/safedose/auth/login.php";
    });
  } else {
    localStorage.removeItem("safedoseUser");
    window.location.href = "/safedose/auth/login.php";
  }
}
