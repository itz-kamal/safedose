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

    <div id="alertBox" class="mb-3"></div>

    <div class="card shadow-sm border-0 mb-3">
      <div class="card-body">
        <div class="row g-2 align-items-center">
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0">
                <i class="fa-solid fa-magnifying-glass text-muted"></i>
              </span>
              <input type="text" class="form-control border-start-0" id="searchInput"
                placeholder="Search by name or manufacturer..." />
            </div>
          </div>
          <div class="col-md-2">
            <select class="form-select" id="filterCategory">
              <option value="">All Categories</option>
            </select>
          </div>
          <div class="col-md-2">
            <select class="form-select" id="filterStock">
              <option value="">All Stock Levels</option>
              <option value="out">Out of Stock (0)</option>
              <option value="low">Low Stock (&le;5)</option>
              <option value="ok">In Stock (&gt;5)</option>
            </select>
          </div>
          <div class="col-md-2">
            <select class="form-select" id="filterExpiry">
              <option value="">All Expiry</option>
              <option value="expired">Expired</option>
              <option value="soon">Expiring in 30 days</option>
              <option value="ok">Valid (&gt;30 days)</option>
            </select>
          </div>
          <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" id="clearFiltersBtn">
              <i class="fa-solid fa-rotate-left me-1"></i> Clear
            </button>
          </div>
        </div>
      </div>
    </div>

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
                <th>Strength</th>
                <th>Qty</th>
                <th>Price (£)</th>
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
        <div class="px-3 py-2 border-top text-muted small" id="tableFooter"></div>
      </div>
    </div>

  </div>

  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="editModalLabel">Edit Medicine</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editMedicineForm">
            <input type="hidden" id="editId" />
            <div class="row mb-3">
              <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label fw-semibold">Medicine Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editName" placeholder="e.g. Paracetamol" />
                <div class="invalid-feedback" id="editNameError"></div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Generic Name</label>
                <input type="text" class="form-control" id="editGenericName" placeholder="e.g. Acetaminophen" />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                <select class="form-select" id="editCategory">
                  <option value="">Select category</option>
                </select>
                <div class="invalid-feedback" id="editCategoryError"></div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Dosage Form <span class="text-danger">*</span></label>
                <select class="form-select" id="editDosageForm">
                  <option value="">Select form</option>
                </select>
                <div class="invalid-feedback" id="editDosageFormError"></div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4 mb-3 mb-md-0">
                <label class="form-label fw-semibold">Dosage Strength <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editDosageStrength" placeholder="e.g. 500mg" />
                <div class="invalid-feedback" id="editDosageStrengthError"></div>
              </div>
              <div class="col-md-4 mb-3 mb-md-0">
                <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="editQuantity" min="0" placeholder="0" />
                <div class="invalid-feedback" id="editQuantityError"></div>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Unit Price (£) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="editUnitPrice" min="0" step="0.01" placeholder="0.00" />
                <div class="invalid-feedback" id="editUnitPriceError"></div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label fw-semibold">Expiry Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="editExpiryDate" />
                <div class="invalid-feedback" id="editExpiryDateError"></div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Manufacturer</label>
                <input type="text" class="form-control" id="editManufacturer" placeholder="e.g. GlaxoSmithKline" />
              </div>
            </div>
            <div class="mb-2">
              <label class="form-label fw-semibold">Description</label>
              <textarea class="form-control" id="editDescription" rows="3" placeholder="Optional notes..."></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary-custom text-white fw-semibold" id="saveEditBtn">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold" id="deleteModalLabel">Delete Medicine</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center py-3">
          <div class="mb-3">
            <i class="fa-solid fa-trash-can fa-3x text-danger"></i>
          </div>
          <p class="mb-1">Are you sure you want to delete</p>
          <p class="fw-bold fs-5" id="deleteMedicineName"></p>
          <p class="text-muted small mb-0">This action cannot be undone.</p>
        </div>
        <div class="modal-footer border-0 justify-content-center gap-2">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger px-4 fw-semibold" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../public/js/validate.js"></script>
  <script src="../public/js/constants.js"></script>
  <script src="../public/js/medicine-list.js"></script>
</body>
</html>
