<div class="navigation_bar">

  <div class="logo">
    <img src="/safedose/images/logo.svg" alt="SafeDose Logo" width="50">
    <span>SafeDose</span>
  </div>

  <ul class="nav-items">
    <li>
      <a href="/safedose/dashboard/staff/index.php">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
      </a>
    </li>
  </ul>

  <div class="logout">
    <button onclick="logout()">
      <i class="fa-solid fa-right-from-bracket"></i>
      <span>Logout</span>
    </button>
  </div>
</div>

<script>
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
</script>
