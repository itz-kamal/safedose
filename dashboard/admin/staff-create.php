<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../public/css/dashboard.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../public/css/style.css">
  <link rel="shortcut icon" href="/safedose/images/logo.svg" type="image/x-icon">
  <script src="../../public/js/dashboard.js"></script>
  <title>SafeDose - Create Staff</title>
</head>
<body>

  <?php include("../../include/sidebar.php"); ?>

  <div class="greeting">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-md-9">
        <div class="card shadow-sm border-1 p-4">
          <div class="card-body">

            <div class="text-center mb-4">
              <h2 class="fw-bold">Create Staff Account</h2>
              <p class="text-muted">Add a new staff member to the SafeDose system.</p>
            </div>

            <div id="staffSuccess" class="alert alert-success d-none"></div>

            <form id="staffCreateForm">
              <div class="row mb-3">
                <div class="col-md-12">
                  <label for="staffName" class="form-label fw-semibold">Full Name</label>
                  <input type="text" class="form-control" id="staffName" placeholder="John Doe" autocomplete="name" />
                  <div class="invalid-feedback" id="nameError"></div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                  <label for="email" class="form-label fw-semibold">Email</label>
                  <input type="email" class="form-control" id="email" placeholder="staff@safedose.com" autocomplete="email" />
                  <div class="invalid-feedback" id="emailError"></div>
                </div>
                <div class="col-md-6">
                  <label for="phoneNumber" class="form-label fw-semibold">Phone Number</label>
                  <input type="tel" class="form-control" id="phoneNumber" placeholder="07011234567" autocomplete="tel" />
                  <div class="invalid-feedback" id="phoneError"></div>
                </div>
              </div>

              <div class="row mb-4">
                <div class="col-md-12">
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
              </div>

              <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-semibold text-white">
                Create Staff Account
              </button>

              <div id="staffError" class="alert alert-danger mt-3 d-none"></div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../public/js/validate.js"></script>
  <script src="../../public/js/staff-create.js"></script>
</body>
</html>
