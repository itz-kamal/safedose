<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/safedose/public/css/dashboard.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="/safedose/public/js/dashboard.js"></script>
  <title>SafeDose - Create Staff</title>
</head>
<body>

<?php include("/opt/lampp/htdocs/safedose/include/sidebar.php"); ?>

<div class="greeting">
  <h5 class="fw-semibold mb-1">Create Staff Account</h5>
  <p class="text-muted mb-4">Add a new staff member to the system.</p>

  <div id="staffSuccess" class="alert alert-success d-none"></div>

  <form id="staffCreateForm" style="max-width: 500px;">
    <div class="mb-3">
      <label for="staffName" class="form-label fw-semibold">Full Name</label>
      <input type="text" class="form-control" id="staffName" />
      <div class="invalid-feedback" id="nameError"></div>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label fw-semibold">Email</label>
      <input type="email" class="form-control" id="email" />
      <div class="invalid-feedback" id="emailError"></div>
    </div>

    <div class="mb-4">
      <label for="password" class="form-label fw-semibold">Generated Password</label>
      <div class="input-group">
        <input type="text" class="form-control" id="password" readonly />
        <button type="button" class="btn btn-outline-secondary" onclick="generatePassword()" title="Regenerate">
          <i class="fas fa-rotate-right"></i>
        </button>
        <button type="button" class="btn btn-outline-secondary" onclick="copyPassword()" title="Copy">
          <i class="fas fa-clipboard" id="copyIcon"></i>
        </button>
      </div>
      <div class="form-text">Share this password with the staff member after creating their account.</div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
      Create Staff Account
    </button>

    <div id="staffError" class="alert alert-danger mt-3 d-none"></div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="/safedose/public/js/validate.js"></script>
<script src="/safedose/public/js/staff-create.js"></script>
</body>
</html>
