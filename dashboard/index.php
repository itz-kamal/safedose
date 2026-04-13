<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../public/css/dashboard.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../public/css/style.css">
  <link rel="shortcut icon" href="/safedose/images/logo.svg" type="image/x-icon">
  <script src="../public/js/dashboard.js"></script>
  <title>SafeDose - Dashboard</title>
</head>
<body>

  <?php include("../include/sidebar.php"); ?>

  <div class="greeting">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h5 id="greeting" class="fw-semibold mb-0"></h5>
        <p class="text-muted small mb-0" id="currentDate"></p>
      </div>
      <a href="/safedose/dashboard/add-medicine.php" class="btn btn-primary-custom text-white fw-semibold">
        <i class="fa-solid fa-plus me-1"></i> Add Medicine
      </a>
    </div>

    <div id="notificationBanners" class="mb-4"></div>

    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-3 p-3" style="background:#e8f4fd;">
              <i class="fa-solid fa-pills fa-lg" style="color:#0081cc;"></i>
            </div>
            <div>
              <div class="text-muted small">Total Medicines</div>
              <div class="fw-bold fs-4" id="statTotal">—</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-3 p-3" style="background:#fdecea;">
              <i class="fa-solid fa-triangle-exclamation fa-lg" style="color:#dc3545;"></i>
            </div>
            <div>
              <div class="text-muted small">Expired</div>
              <div class="fw-bold fs-4" id="statExpired">—</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-3 p-3" style="background:#fff8e1;">
              <i class="fa-solid fa-clock fa-lg" style="color:#ffc107;"></i>
            </div>
            <div>
              <div class="text-muted small">Expiring Soon</div>
              <div class="fw-bold fs-4" id="statExpiringSoon">—</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-3 p-3" style="background:#fff3e0;">
              <i class="fa-solid fa-box-open fa-lg" style="color:#fd7e14;"></i>
            </div>
            <div>
              <div class="text-muted small">Low Stock</div>
              <div class="fw-bold fs-4" id="statLowStock">—</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold mb-0">Recent Medicines</h6>
              <a href="/safedose/dashboard/list-medicines.php" class="text-decoration-none small" style="color:#0081cc;">
                View all <i class="fa-solid fa-arrow-right ms-1"></i>
              </a>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="fw-semibold small">Name</th>
                    <th class="fw-semibold small">Category</th>
                    <th class="fw-semibold small">Qty</th>
                    <th class="fw-semibold small">Expiry</th>
                    <th class="fw-semibold small">Status</th>
                  </tr>
                </thead>
                <tbody id="recentMedicinesBody">
                  <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                      <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                      Loading...
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Quick Actions</h6>
            <div class="d-grid gap-2">
              <a href="/safedose/dashboard/add-medicine.php" class="btn btn-primary-custom text-white fw-semibold text-start">
                <i class="fa-solid fa-plus me-2"></i> Add New Medicine
              </a>
              <a href="/safedose/dashboard/list-medicines.php" class="btn btn-outline-secondary fw-semibold text-start">
                <i class="fa-solid fa-list me-2"></i> View Inventory
              </a>
              <a href="/safedose/dashboard/list-medicines.php?filter=expired" class="btn btn-outline-danger fw-semibold text-start">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> View Expired
              </a>
              <a href="/safedose/dashboard/list-medicines.php?filter=low" class="btn btn-outline-warning fw-semibold text-start">
                <i class="fa-solid fa-box-open me-2"></i> View Low Stock
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../public/js/constants.js"></script>
  <script src="../public/js/dashboard-index.js"></script>
  <script>
    document.getElementById("currentDate").textContent = new Date().toLocaleDateString("en-GB", {
      weekday: "long", year: "numeric", month: "long", day: "numeric"
    });
  </script>
</body>
</html>
