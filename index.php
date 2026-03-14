<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="images/logo.svg" type="image/x-icon">
  <title>Safe Dose</title>
</head>
<body>
  <h1>Hello Safe Dose</h1>

  <script>
    fetch("/safedose/controller/auth/adminExists.php", { method: "GET" })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        window.location.href = data.adminExists ? "/safedose/auth/login.php" : "/safedose/auth/register.php";
      } else {
        window.location.href = "/safedose/auth/login.php";
      }
    })
    .catch((err) => {
      console.error(err);
    });
  </script>
</body>
</html>