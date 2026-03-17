const form = document.getElementById("addMedicineForm");
const submitBtn = form.querySelector("button[type='submit']");
const medicineError = document.getElementById("medicineError");
const medicineSuccess = document.getElementById("medicineSuccess");

function showError(fieldId, errorId, message) {
  document.getElementById(fieldId).classList.add("is-invalid");
  document.getElementById(errorId).textContent = message;
}

function clearError(fieldId, errorId) {
  document.getElementById(fieldId).classList.remove("is-invalid");
  document.getElementById(errorId).textContent = "";
}

function validateTextField(fieldId, errorId, message) {
  const value = document.getElementById(fieldId).value.trim();
  if (!value) {
    showError(fieldId, errorId, message);
    return false;
  }
  clearError(fieldId, errorId);
  return true;
}

function validateSelect(fieldId, errorId, message) {
  const value = document.getElementById(fieldId).value;
  if (!value) {
    showError(fieldId, errorId, message);
    return false;
  }
  clearError(fieldId, errorId);
  return true;
}

function validateNumber(fieldId, errorId, label) {
  const value = document.getElementById(fieldId).value;
  if (!value) {
    showError(fieldId, errorId, label + " is required.");
    return false;
  }
  if (Number(value) < 0) {
    showError(fieldId, errorId, label + " cannot be negative.");
    return false;
  }
  clearError(fieldId, errorId);
  return true;
}

function validateExpiry() {
  const value = document.getElementById("expiryDate").value;
  if (!value) {
    showError("expiryDate", "expiryDateError", "Expiry date is required.");
    return false;
  }
  if (new Date(value) <= new Date()) {
    showError("expiryDate", "expiryDateError", "Expiry date must be in the future.");
    return false;
  }
  clearError("expiryDate", "expiryDateError");
  return true;
}

// Blur / change listeners
document.getElementById("medicineName").addEventListener("blur", function () {
  validateTextField("medicineName", "medicineNameError", "Medicine name is required.");
});

document.getElementById("dosageStrength").addEventListener("blur", function () {
  validateTextField("dosageStrength", "dosageStrengthError", "Dosage strength is required.");
});

document.getElementById("quantity").addEventListener("blur", function () {
  validateNumber("quantity", "quantityError", "Quantity");
});

document.getElementById("unitPrice").addEventListener("blur", function () {
  validateNumber("unitPrice", "unitPriceError", "Unit price");
});

document.getElementById("category").addEventListener("change", function () {
  validateSelect("category", "categoryError", "Please select a category.");
});

document.getElementById("dosageForm").addEventListener("change", function () {
  validateSelect("dosageForm", "dosageFormError", "Please select a dosage form.");
});

document.getElementById("expiryDate").addEventListener("change", function () {
  validateExpiry();
});

// Form submit
form.addEventListener("submit", function (e) {
  e.preventDefault();

  const isNameValid = validateTextField("medicineName", "medicineNameError", "Medicine name is required.");
  const isCategoryValid = validateSelect("category", "categoryError", "Please select a category.");
  const isDosageFormValid = validateSelect("dosageForm", "dosageFormError", "Please select a dosage form.");
  const isStrengthValid = validateTextField("dosageStrength", "dosageStrengthError", "Dosage strength is required.");
  const isQuantityValid = validateNumber("quantity", "quantityError", "Quantity");
  const isPriceValid = validateNumber("unitPrice", "unitPriceError", "Unit price");
  const isExpiryValid = validateExpiry();

  if (!isNameValid || !isCategoryValid || !isDosageFormValid || !isStrengthValid || !isQuantityValid || !isPriceValid || !isExpiryValid) {
    return;
  }

  submitBtn.disabled = true;
  submitBtn.textContent = "Adding medicine...";
  medicineError.classList.add("d-none");
  medicineSuccess.classList.add("d-none");

  const formData = new FormData();
  formData.append("token", window.currentUser.token);
  formData.append("name", document.getElementById("medicineName").value.trim());
  formData.append("genericName", document.getElementById("genericName").value.trim());
  formData.append("category", document.getElementById("category").value);
  formData.append("dosage", document.getElementById("dosageForm").value);
  formData.append("dosageStrength", document.getElementById("dosageStrength").value.trim());
  formData.append("quantity", document.getElementById("quantity").value);
  formData.append("price", document.getElementById("unitPrice").value);
  formData.append("expiryDate", document.getElementById("expiryDate").value);
  formData.append("manufacturer", document.getElementById("manufacturer").value.trim());
  formData.append("description", document.getElementById("description").value.trim());

  fetch("/safedose/controller/medicine/add-medicine.php", {
    method: "POST",
    body: formData,
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      if (data.success) {
        medicineSuccess.textContent = data.message;
        medicineSuccess.classList.remove("d-none");
        form.reset();
      } else if (data.message === "Unauthorized") {
        window.location.href = "/safedose/auth/login.php";
      } else {
        medicineError.textContent = data.message;
        medicineError.classList.remove("d-none");
      }
      submitBtn.disabled = false;
      submitBtn.textContent = "Add Medicine";
    })
    .catch(function () {
      medicineError.textContent = "Something went wrong. Please try again.";
      medicineError.classList.remove("d-none");
      submitBtn.disabled = false;
      submitBtn.textContent = "Add Medicine";
    });
});
