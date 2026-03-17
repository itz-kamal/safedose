<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../public/css/dashboard.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="/safedose/images/logo.svg" type="image/x-icon">
  <script src="../../public/js/dashboard.js"></script>
  <title>SafeDose - Staff Dashboard</title>
</head>
<body>

 <?php include("../../include/sidebar.php"); ?>

  <div class="greeting">
    <h5 id="greeting" class="fw-semibold mb-1"></h5>
    <h3>Latest Information</h3>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    if (window.currentUser && window.currentUser.role === 'admin') {
      window.location.href = '/safedose/dashboard/admin/index.php';
    }
  </script>
</body>
</html>
