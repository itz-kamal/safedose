<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../public/css/dashboard.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../public/css/style.css">
  <link rel="shortcut icon" href="/safedose/images/logo.svg" type="image/x-icon">
  <script src="../public/js/dashboard.js"></script>
  <title>SafeDose - Medicines List</title>
</head>
<body>

  <?php include("../include/sidebar.php"); ?>

  <div class="greeting">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold mb-0">Medicine Inventory</h2>
      <a href="/safedose/dashboard/add-medicine.php" class="btn btn-primary-custom text-white fw-semibold">
        <i class="fa-solid fa-plus me-1"></i> Add Medicine
      </a>
    </div>

    <div id="alertBox" class="d-none mb-3"></div>

    <div class="card shadow-sm border-1">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Category</th>
                <th>Dosage Form</th>
                <th>Dosage Strength	</th>
                <th>Qty</th>
                <th>Price (₦)</th>
                <th>Expiry</th>
                <th>Manufacturer</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="medicineTableBody">
              <tr>
                <td colspan="10" class="text-center py-4 text-muted">
                  <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                  Loading medicines...
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../public/js/medicine-list.js"></script>
</body>
</html>
