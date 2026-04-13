const tableBody = document.getElementById("staffTableBody");
const alertBox = document.getElementById("alertBox");

function showAlert(message, type) {
  alertBox.className = `alert alert-${type}`;
  alertBox.textContent = message;
  alertBox.classList.remove("d-none");
  setTimeout(() => alertBox.classList.add("d-none"), 4000);
}

function handleUnauthorized(data) {
  if (!data.success && data.message === "Unauthorized") {
    window.location.href = "/safedose/auth/login.php";
    return true;
  }
  return false;
}

function loadStaff() {
  const formData = new FormData();
  formData.append("token", window.currentUser.token);

  fetch("/safedose/controller/user/get-staff.php", { method: "POST", body: formData })
    .then((r) => {
      if (r.status === 401) {
        window.location.href = "/safedose/auth/login.php";
        return;
      }
      return r.text().then((html) => {
        tableBody.innerHTML = html;
      });
    })
    .catch(() => {
      tableBody.innerHTML =
        '<tr><td colspan="7" class="text-center py-4 text-danger">Failed to load staff. Please refresh.</td></tr>';
    });
}

tableBody.addEventListener("click", (e) => {
  const statusBtn = e.target.closest(".status-btn");
  const roleBtn = e.target.closest(".role-btn");

  if (statusBtn) {
    const staffId = statusBtn.dataset.id;
    const newStatus = statusBtn.dataset.status;
    statusBtn.disabled = true;
    statusBtn.textContent = "Updating…";

    const formData = new FormData();
    formData.append("userId", staffId);
    formData.append("status", newStatus);
    formData.append("token", window.currentUser.token);

    fetch("/safedose/controller/user/update-status.php", { method: "POST", body: formData })
      .then((res) => res.json())
      .then((data) => {
        if (handleUnauthorized(data)) return;
        if (data.success) {
          loadStaff();
          showAlert("Staff status updated successfully.", "success");
        } else {
          showAlert(data.message, "danger");
          statusBtn.disabled = false;
          statusBtn.textContent = newStatus === "inactive" ? "Deactivate" : "Reactivate";
        }
      })
      .catch(() => {
        showAlert("Something went wrong. Please try again.", "danger");
        statusBtn.disabled = false;
        statusBtn.textContent = newStatus === "inactive" ? "Deactivate" : "Reactivate";
      });
  }

  if (roleBtn) {
    const staffId = roleBtn.dataset.id;
    const newRole = roleBtn.dataset.role;
    roleBtn.disabled = true;
    roleBtn.textContent = "Updating…";

    const formData = new FormData();
    formData.append("userId", staffId);
    formData.append("role", newRole);
    formData.append("token", window.currentUser.token);

    fetch("/safedose/controller/user/update-role.php", { method: "POST", body: formData })
      .then((r) => r.json())
      .then((data) => {
        if (handleUnauthorized(data)) return;
        if (data.success) {
          loadStaff();
          showAlert(
            newRole === "admin" ? "User promoted to Admin." : "User demoted to Staff.",
            "success",
          );
        } else {
          showAlert(data.message, "danger");
          roleBtn.disabled = false;
          roleBtn.textContent = newRole === "admin" ? "Promote to Admin" : "Demote to Staff";
        }
      })
      .catch(() => {
        showAlert("Something went wrong. Please try again.", "danger");
        roleBtn.disabled = false;
        roleBtn.textContent = newRole === "admin" ? "Promote to Admin" : "Demote to Staff";
      });
  }
});

loadStaff();
