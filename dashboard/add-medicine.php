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
  <title>SafeDose - Add Medicine</title>
</head>
<body>

  <?php include("../include/sidebar.php"); ?>

  <div class="greeting">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm border-1 p-4">
          <div class="card-body">

            <div class="text-center mb-4">
              <h2 class="fw-bold">Add Medicine</h2>
              <p class="text-muted">Add a new medicine to the SafeDose inventory.</p>
            </div>

            <div id="medicineSuccess" class="alert alert-success d-none"></div>

            <form id="addMedicineForm">

              <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                  <label for="medicineName" class="form-label fw-semibold">Medicine Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="medicineName" placeholder="e.g. Paracetamol" />
                  <div class="invalid-feedback" id="medicineNameError"></div>
                </div>
                <div class="col-md-6">
                  <label for="genericName" class="form-label fw-semibold">Generic Name</label>
                  <input type="text" class="form-control" id="genericName" placeholder="e.g. Acetaminophen" />
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                  <label for="category" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                  <select class="form-select" id="category">
                    <option value="">Select category</option>
                    <option value="antibiotic">Antibiotic</option>
                    <option value="analgesic">Analgesic</option>
                    <option value="antihistamine">Antihistamine</option>
                    <option value="antiviral">Antiviral</option>
                    <option value="antifungal">Antifungal</option>
                    <option value="cardiovascular">Cardiovascular</option>
                    <option value="diabetes">Diabetes</option>
                    <option value="other">Other</option>
                  </select>
                  <div class="invalid-feedback" id="categoryError"></div>
                </div>
                <div class="col-md-6">
                  <label for="dosageForm" class="form-label fw-semibold">Dosage Form <span class="text-danger">*</span></label>
                  <select class="form-select" id="dosageForm">
                    <option value="">Select form</option>
                    <option value="tablet">Tablet</option>
                    <option value="capsule">Capsule</option>
                    <option value="syrup">Syrup</option>
                    <option value="injection">Injection</option>
                    <option value="cream">Cream</option>
                    <option value="drops">Drops</option>
                    <option value="inhaler">Inhaler</option>
                    <option value="other">Other</option>
                  </select>
                  <div class="invalid-feedback" id="dosageFormError"></div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-4 mb-3 mb-md-0">
                  <label for="dosageStrength" class="form-label fw-semibold">Dosage Strength <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="dosageStrength" placeholder="e.g. 500mg" />
                  <div class="invalid-feedback" id="dosageStrengthError"></div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                  <label for="quantity" class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="quantity" placeholder="0" min="0" />
                  <div class="invalid-feedback" id="quantityError"></div>
                </div>
                <div class="col-md-4">
                  <label for="unitPrice" class="form-label fw-semibold">Unit Price (£) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="unitPrice" placeholder="0.00" min="0" step="0.01" />
                  <div class="invalid-feedback" id="unitPriceError"></div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                  <label for="expiryDate" class="form-label fw-semibold">Expiry Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="expiryDate" />
                  <div class="invalid-feedback" id="expiryDateError"></div>
                </div>
                <div class="col-md-6">
                  <label for="manufacturer" class="form-label fw-semibold">Manufacturer</label>
                  <input type="text" class="form-control" id="manufacturer" placeholder="e.g. GlaxoSmithKline" />
                </div>
              </div>

              <div class="mb-4">
                <label for="description" class="form-label fw-semibold">Description</label>
                <textarea class="form-control" id="description" rows="3" placeholder="Optional notes about this medicine..."></textarea>
              </div>

              <button type="submit" id="submitBtn" class="btn btn-primary-custom w-100 py-2 fw-semibold text-white">
                Add Medicine
              </button>

              <div id="medicineError" class="alert alert-danger mt-3 d-none"></div>

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../public/js/add-medicine.js"></script>
</body>
</html>
