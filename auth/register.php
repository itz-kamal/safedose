<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SafeDose - Create Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"crossorigin="anonymous"/>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <div class="container">
      <div class="row min-vh-100 align-items-center justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="card shadow-sm border-0 p-4">
            <div class="card-body">
              <div class="text-center mb-4">
                <div>
                  <img src="../images/logo.svg" alt="SafeDose Logo" width="150" />
                </div>
                <h2 class="fw-bold">Create Admin</h2>
                <p class="text-muted">Initialize your pharmacy management system by creating theprimary administrator account.</p>
              </div>
              <form id="createAdminForm">
                <div class="row mb-3">
                  <div class="col-md-12 mb-3 mb-md-0">
                    <label for="name" class="form-label fw-semibold">Full Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Sarah Jhonson" />
                    <div class="invalid-feedback" id="nameError"></div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-md-6 mb-3 mb-md-0">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="admin@safedose.com" />
                    <div class="invalid-feedback" id="emailError"></div>
                  </div>

                  <div class="col-md-6">
                    <label for="phoneNumber" class="form-label fw-semibold">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneNumber" placeholder="09123456789"/>
                    <div class="invalid-feedback" id="phoneError"></div>
                  </div>
                </div>
                <div class="row mb-4">
                  <div class="col-md-6 mb-3 mb-md-0">
                    <label for="password" class="form-label fw-semibold"  >Password</label>
                    <input type="password" class="form-control" id="password" />
                    <div class="invalid-feedback" id="passwordError"></div>
                  </div>

                  <div class="col-md-6">
                    <label for="confirmPassword" class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" />
                    <div class="invalid-feedback" id="confirmPasswordError"></div>
                  </div>
                </div>

                <button type="submit" class="btn w-100 py-2 fw-semibold text-white" style="background-color: #3a8fb7">
                  Create Admin Account
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../public/js/register.js"></script>
  </body>
</html>