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
  <title>SafeDose - Staff List</title>
</head>
<body>

  <?php include("../../include/sidebar.php"); ?>

  <div class="greeting">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h5 class="fw-semibold mb-0">Staff Members</h5>
        <p class="text-muted mb-0 small">Manage all staff accounts</p>
      </div>
      <a href="/safedose/dashboard/admin/staff-create.php" class="btn btn-primary-custom fw-semibold text-white">
        <i class="fas fa-user-plus me-2"></i>Add Staff
      </a>
    </div>

    <div id="alertBox" class="d-none mb-3"></div>

    <div class="card shadow-sm border-1">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="px-4 py-3">#</th>
                <th class="py-3">Name</th>
                <th class="py-3">Email</th>
                <th class="py-3">Phone</th>
                <th class="py-3">Role</th>
                <th class="py-3">Status</th>
                <th class="py-3">Actions</th>
              </tr>
            </thead>
            <tbody id="staffTableBody">
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                  <span class="spinner-border spinner-border-sm me-2"></span>Loading staff...
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../public/js/staff-list.js"></script>
</body>
</html>
