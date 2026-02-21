document.addEventListener("DOMContentLoaded", function () {
  let form = document.getElementById("loginForm");

  let email = document.getElementById("email");
  let password = document.getElementById("password");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    if (email.value.length == 0 || password.value.length == 0) {
      return;
    }
    let formData = new FormData();
    formData.append("email", email.value);
    formData.append("password", password.value);

    fetch("/safedose/controller/login.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        // if (data.success) {
        //   window.location.href = "/safedose/dashboard/index.php";
        // }
      })
      .catch((err) => console.error(err));
  });
});
