let allMedicines = [];
let medicinesById = {};
let deleteTargetId = null;

const tbody = document.getElementById("medicineTableBody");
const alertBox = document.getElementById("alertBox");
const tableFooter = document.getElementById("tableFooter");
const searchInput = document.getElementById("searchInput");
const filterCategory = document.getElementById("filterCategory");
const filterStock = document.getElementById("filterStock");
const filterExpiry = document.getElementById("filterExpiry");
const clearFiltersBtn = document.getElementById("clearFiltersBtn");
const saveEditBtn = document.getElementById("saveEditBtn");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const deleteMedicineName = document.getElementById("deleteMedicineName");

function formatDate(dateStr) {
  if (!dateStr) return "—";
  return new Date(dateStr).toLocaleDateString("en-GB");
}

function daysDiff(dateStr) {
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  return Math.floor((new Date(dateStr) - today) / (1000 * 60 * 60 * 24));
}

function populateSelect(id, values) {
  const sel = document.getElementById(id);
  values.forEach(function (v) {
    const opt = document.createElement("option");
    opt.value = v;
    opt.textContent = v.charAt(0).toUpperCase() + v.slice(1);
    sel.appendChild(opt);
  });
}

function renderNotifications(medicines) {
  let expired = 0,
    expiringSoon = 0,
    lowStock = 0;
  medicines.forEach(function (m) {
    const diff = daysDiff(m.expiry_date);
    if (diff < 0) expired++;
    else if (diff <= 30) expiringSoon++;
    if (parseInt(m.quantity) <= LOW_STOCK_THRESHOLD) lowStock++;
  });

  let html = "";
  if (expired > 0) {
    html += `<div class="alert alert-danger d-flex align-items-center gap-2 mb-2">
      <i class="fa-solid fa-circle-xmark"></i>
      <span><strong>${expired} medicine${expired > 1 ? "s have" : " has"} expired.</strong> Please review and remove expired stock.</span>
    </div>`;
  }
  if (expiringSoon > 0) {
    html += `<div class="alert alert-warning d-flex align-items-center gap-2 mb-2">
      <i class="fa-solid fa-clock"></i>
      <span><strong>${expiringSoon} medicine${expiringSoon > 1 ? "s are" : " is"} expiring within 30 days.</strong> Please review.</span>
    </div>`;
  }
  if (lowStock > 0) {
    html += `<div class="alert alert-warning d-flex align-items-center gap-2 mb-2">
      <i class="fa-solid fa-box-open"></i>
      <span><strong>${lowStock} medicine${lowStock > 1 ? "s have" : " has"} low stock (&le;${LOW_STOCK_THRESHOLD} units).</strong> Consider restocking.</span>
    </div>`;
  }
  alertBox.innerHTML = html;
}

function renderTable(medicines) {
  if (medicines.length === 0) {
    tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-muted">No medicines found.</td></tr>`;
    tableFooter.textContent = "";
    return;
  }

  tbody.innerHTML = medicines
    .map(function (m, i) {
      const diff = daysDiff(m.expiry_date);
      const rowClass = diff < 0 ? "table-danger" : diff <= 30 ? "table-warning" : "";
      const qtyClass =
        parseInt(m.quantity) === 0
          ? "text-danger fw-bold"
          : parseInt(m.quantity) <= LOW_STOCK_THRESHOLD
            ? "text-warning fw-bold"
            : "";

      return `<tr class="${rowClass}">
      <td>${i + 1}</td>
      <td>
        <div class="fw-semibold">${m.name}</div>
        ${m.generic_name ? `<div class="text-muted small">${m.generic_name}</div>` : ""}
      </td>
      <td class="text-capitalize">${m.category}</td>
      <td class="text-capitalize">${m.dosage}</td>
      <td>${m.dosage_strength}</td>
      <td class="${qtyClass}">${m.quantity}</td>
      <td>£${parseFloat(m.price).toFixed(2)}</td>
      <td>${formatDate(m.expiry_date)}</td>
      <td>${m.manufacturer || "—"}</td>
      <td>
        <button class="btn btn-sm btn-warning edit-btn" data-id="${m.id}">Edit</button>
        <button class="btn btn-sm btn-danger delete-btn" data-id="${m.id}">Delete</button>
      </td>
    </tr>`;
    })
    .join("");

  tableFooter.textContent = `Showing ${medicines.length} medicine${medicines.length !== 1 ? "s" : ""}`;

  tbody.querySelectorAll(".edit-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
      const m = medicinesById[this.dataset.id];
      if (!m) return;
      document.getElementById("editId").value = m.id;
      document.getElementById("editName").value = m.name;
      document.getElementById("editGenericName").value = m.generic_name || "";
      document.getElementById("editCategory").value = m.category;
      document.getElementById("editDosageForm").value = m.dosage;
      document.getElementById("editDosageStrength").value = m.dosage_strength;
      document.getElementById("editQuantity").value = m.quantity;
      document.getElementById("editUnitPrice").value = m.price;
      document.getElementById("editExpiryDate").value = m.expiry_date;
      document.getElementById("editManufacturer").value = m.manufacturer || "";
      document.getElementById("editDescription").value = m.description || "";
      bootstrap.Modal.getOrCreateInstance(document.getElementById("editModal")).show();
    });
  });

  tbody.querySelectorAll(".delete-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
      deleteTargetId = this.dataset.id;
      deleteMedicineName.textContent = medicinesById[this.dataset.id].name;
      bootstrap.Modal.getOrCreateInstance(document.getElementById("deleteModal")).show();
    });
  });
}

function applyFilters() {
  const search = searchInput.value.toLowerCase().trim();
  const category = filterCategory.value;
  const stock = filterStock.value;
  const expiry = filterExpiry.value;

  const filtered = allMedicines.filter(function (m) {
    if (search) {
      const matchesName = m.name.toLowerCase().includes(search);
      const matchesMfr = (m.manufacturer || "").toLowerCase().includes(search);
      if (!matchesName && !matchesMfr) return false;
    }
    if (category && m.category !== category) return false;
    if (stock) {
      const qty = parseInt(m.quantity);
      if (stock === "out" && qty !== 0) return false;
      if (stock === "low" && (qty === 0 || qty > LOW_STOCK_THRESHOLD)) return false;
      if (stock === "ok" && qty <= LOW_STOCK_THRESHOLD) return false;
    }
    if (expiry) {
      const diff = daysDiff(m.expiry_date);
      if (expiry === "expired" && diff >= 0) return false;
      if (expiry === "soon" && (diff < 0 || diff > 30)) return false;
      if (expiry === "ok" && diff <= 30) return false;
    }
    return true;
  });

  renderTable(filtered);
}

function loadMedicines() {
  tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-muted">
    <div class="spinner-border spinner-border-sm me-2" role="status"></div>Loading medicines...
  </td></tr>`;

  fetch(
    `/safedose/controller/medicine/all-medicine.php?token=${encodeURIComponent(window.currentUser.token)}`,
  )
    .then(function (r) {
      return r.json();
    })
    .then(function (data) {
      if (data.success) {
        allMedicines = data.data;
        medicinesById = {};
        allMedicines.forEach(function (m) {
          medicinesById[m.id] = m;
        });
        renderNotifications(allMedicines);
        applyFilters();
      } else {
        if (handleAuthError(data)) return;
        tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-danger">${data.message}</td></tr>`;
      }
    })
    .catch(function () {
      tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-danger">Failed to load medicines. Please refresh.</td></tr>`;
    });
}

saveEditBtn.addEventListener("click", function () {
  const isValid = [
    validateTextField("editName", "editNameError", "Medicine name is required."),
    validateSelect("editCategory", "editCategoryError", "Please select a category."),
    validateSelect("editDosageForm", "editDosageFormError", "Please select a dosage form."),
    validateTextField(
      "editDosageStrength",
      "editDosageStrengthError",
      "Dosage strength is required.",
    ),
    validateNumber("editQuantity", "editQuantityError", "Quantity"),
    validateNumber("editUnitPrice", "editUnitPriceError", "Unit price"),
    validateExpiry("editExpiryDate", "editExpiryDateError"),
  ].every(Boolean);

  if (!isValid) return;

  const formData = new FormData();
  formData.append("token", window.currentUser.token);
  formData.append("id", document.getElementById("editId").value);
  formData.append("name", document.getElementById("editName").value);
  formData.append("genericName", document.getElementById("editGenericName").value);
  formData.append("category", document.getElementById("editCategory").value);
  formData.append("dosage", document.getElementById("editDosageForm").value);
  formData.append("dosageStrength", document.getElementById("editDosageStrength").value);
  formData.append("quantity", document.getElementById("editQuantity").value);
  formData.append("price", document.getElementById("editUnitPrice").value);
  formData.append("expiryDate", document.getElementById("editExpiryDate").value);
  formData.append("manufacturer", document.getElementById("editManufacturer").value);
  formData.append("description", document.getElementById("editDescription").value);

  saveEditBtn.disabled = true;
  saveEditBtn.textContent = "Saving...";

  fetch("/safedose/controller/medicine/update-medicine.php", {
    method: "POST",
    body: formData,
  })
    .then(function (r) {
      return r.json();
    })
    .then(function (data) {
      if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById("editModal")).hide();
        loadMedicines();
      } else {
        if (handleAuthError(data)) return;
        alert(data.message);
      }
      saveEditBtn.disabled = false;
      saveEditBtn.textContent = "Save Changes";
    })
    .catch(function () {
      alert("Something went wrong. Please try again.");
      saveEditBtn.disabled = false;
      saveEditBtn.textContent = "Save Changes";
    });
});

confirmDeleteBtn.addEventListener("click", function () {
  const formData = new FormData();
  formData.append("token", window.currentUser.token);
  formData.append("id", deleteTargetId);

  confirmDeleteBtn.disabled = true;
  confirmDeleteBtn.textContent = "Deleting...";

  fetch("/safedose/controller/medicine/delete-medicine.php", {
    method: "POST",
    body: formData,
  })
    .then(function (r) {
      return r.json();
    })
    .then(function (data) {
      if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById("deleteModal")).hide();
        loadMedicines();
      } else {
        if (handleAuthError(data)) return;
        alert(data.message);
      }
      confirmDeleteBtn.disabled = false;
      confirmDeleteBtn.textContent = "Delete";
    })
    .catch(function () {
      alert("Something went wrong. Please try again.");
      confirmDeleteBtn.disabled = false;
      confirmDeleteBtn.textContent = "Delete";
    });
});

clearFiltersBtn.addEventListener("click", function () {
  searchInput.value = "";
  filterCategory.value = "";
  filterStock.value = "";
  filterExpiry.value = "";
  renderNotifications(allMedicines);
  renderTable(allMedicines);
});

searchInput.addEventListener("input", applyFilters);
filterCategory.addEventListener("change", applyFilters);
filterStock.addEventListener("change", applyFilters);
filterExpiry.addEventListener("change", applyFilters);

function applyUrlFilters() {
  const params = new URLSearchParams(window.location.search);
  const filter = params.get("filter");
  if (filter === "expired") {
    filterExpiry.value = "expired";
  } else if (filter === "low") {
    filterStock.value = "low";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("editExpiryDate").min = new Date().toISOString().split("T")[0];

  fetch("/safedose/controller/medicine/enums.php")
    .then(function (r) {
      return r.json();
    })
    .then(function (data) {
      populateSelect("filterCategory", data.categories);
      populateSelect("editCategory", data.categories);
      populateSelect("editDosageForm", data.dosageForms);
    })
    .then(function () {
      applyUrlFilters();
      loadMedicines();
    });
});
