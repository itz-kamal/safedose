const form = document.getElementById("addMedicineForm");

const medicineName = document.getElementById("medicineName");
const genericName = document.getElementById("genericName");
const category = document.getElementById("category");
const dosageForm = document.getElementById("dosageForm");
const dosageStrength = document.getElementById("dosageStrength");
const quantity = document.getElementById("quantity");
const unitPrice = document.getElementById("unitPrice");
const expiryDate = document.getElementById("expiryDate");
const manufacturer = document.getElementById("manufacturer");
const description = document.getElementById("description");
const submitBtn = document.getElementById("submitBtn");

const medicineSuccess = document.getElementById("medicineSuccess");
const medicineError = document.getElementById("medicineError");

form.addEventListener("submit", (e) => {
  e.preventDefault();

  submitBtn.disable = true;
  submitBtn.textContent = "Adding medicine";

  const formData = new FormData();
  formData.append("name", medicineName.value);
  formData.append("genericName", genericName.value);
  formData.append("category", category.value);
  formData.append("dosage", dosageForm.value);
  formData.append("dosageStrength", dosageStrength.value);
  formData.append("quantity", quantity.value);
  formData.append("price", unitPrice.value);
  formData.append("expiryDate", expiryDate.value);
  formData.append("manufacturer", manufacturer.value);
  formData.append("description", description.value);
  formData.append("token", window.currentUser.token);

  fetch("/safedose/controller/medicine/add-medicine.php", {
    method: "POST",
    body: formData,
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        medicineSuccess.textContent = data.message;
        medicineSuccess.classList.remove("d-none");
        form.reset();
      } else {
        medicineError.textContent = data.message;
        medicineError.classList.remove("d-none");
      }
      submitBtn.disabled = false;
      submitBtn.textContent = "Add Medicine";
    })
    .catch(() => {
      medicineError.textContent = "Something went wrong. Please try again.";
      medicineError.classList.remove("d-none");
      submitBtn.disabled = false;
      submitBtn.textContent = "Add Medicine";
    });
});
