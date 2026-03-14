<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SafeDose - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"crossorigin="anonymous"/>
    <link rel="stylesheet" href="../public/css/style.css" />
    <link rel="shortcut icon" href="../images/logo.svg" type="image/x-icon">
</head>
<body>
  <div class="container">
    <div class="row min-vh-100 align-items-center justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="card shadow-sm border-1 p-4">
          <div class="card-body">
            <div class="text-center mb-4">
              <div>
                <img src="../images/logo.svg" alt="SafeDose Logo" width="150" />
              </div>
              <h2 class="fw-bold">Login</h2>
            </div>
            
            <form id="loginForm">
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control" id="email" autocomplete="email" />
                <div class="invalid-feedback" id="emailError"></div>
              </div>

              <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control" id="password" autocomplete="password" />
                <div class="invalid-feedback" id="passwordError"></div>
              </div>

              <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-semibold text-white">
                Login
              </button>
              <div id="loginError" class="alert alert-danger mt-3 d-none"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
       <script src="../public/js/validate.js"></script>
    <script src="../public/js/login.js"></script>
  </body>
</html>