function daysDiff(dateStr) {
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  return Math.floor((new Date(dateStr) - today) / (1000 * 60 * 60 * 24));
}

function formatDate(dateStr) {
  if (!dateStr) return "—";
  return new Date(dateStr).toLocaleDateString("en-GB");
}

function renderStats(medicines) {
  let expired = 0,
    expiringSoon = 0,
    lowStock = 0;

  medicines.forEach(function (m) {
    const diff = daysDiff(m.expiry_date);
    if (diff < 0) expired++;
    else if (diff <= 30) expiringSoon++;
    if (parseInt(m.quantity) <= LOW_STOCK_THRESHOLD) lowStock++;
  });

  document.getElementById("statTotal").textContent = medicines.length;
  document.getElementById("statExpired").textContent = expired;
  document.getElementById("statExpiringSoon").textContent = expiringSoon;
  document.getElementById("statLowStock").textContent = lowStock;

  renderNotifications(expired, expiringSoon, lowStock);
}

function renderNotifications(expired, expiringSoon, lowStock) {
  const container = document.getElementById("notificationBanners");
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
      <span><strong>${expiringSoon} medicine${expiringSoon > 1 ? "s are" : " is"} expiring within 30 days.</strong> Please Review.</span>
    </div>`;
  }
  if (lowStock > 0) {
    html += `<div class="alert alert-warning d-flex align-items-center gap-2 mb-2">
      <i class="fa-solid fa-box-open"></i>
      <span><strong>${lowStock} medicine${lowStock > 1 ? "s have" : " has"} low stock (&le;${LOW_STOCK_THRESHOLD} units).</strong> Consider restocking.</span>
    </div>`;
  }

  container.innerHTML = html;
}

function renderRecentMedicines(medicines) {
  const tbody = document.getElementById("recentMedicinesBody");
  const recent = medicines.slice(-5).reverse();

  if (recent.length === 0) {
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-3 text-muted">No medicines found.</td></tr>`;
    return;
  }

  tbody.innerHTML = recent
    .map(function (m) {
      const diff = daysDiff(m.expiry_date);
      let statusBadge;
      if (diff < 0) {
        statusBadge = `<span class="badge bg-danger">Expired</span>`;
      } else if (diff <= 30) {
        statusBadge = `<span class="badge bg-warning text-dark">Expiring Soon</span>`;
      } else {
        statusBadge = `<span class="badge bg-success">Valid</span>`;
      }

      const qtyClass =
        parseInt(m.quantity) === 0
          ? "text-danger fw-bold"
          : parseInt(m.quantity) <= LOW_STOCK_THRESHOLD
            ? "text-warning fw-bold"
            : "";

      return `<tr>
      <td>
        <div class="fw-semibold small">${m.name}</div>
        ${m.generic_name ? `<div class="text-muted" style="font-size:0.75rem;">${m.generic_name}</div>` : ""}
      </td>
      <td><span class="text-capitalize small">${m.category}</span></td>
      <td class="${qtyClass} small">${m.quantity}</td>
      <td class="small">${formatDate(m.expiry_date)}</td>
      <td>${statusBadge}</td>
    </tr>`;
    })
    .join("");
}

document.addEventListener("DOMContentLoaded", function () {
  fetch(
    `/safedose/controller/medicine/all-medicine.php?token=${encodeURIComponent(window.currentUser.token)}`,
  )
    .then(function (r) {
      return r.json();
    })
    .then(function (data) {
      if (data.success && data.data) {
        renderStats(data.data);
        renderRecentMedicines(data.data);
      } else {
        if (handleAuthError(data)) return;
        document.getElementById("recentMedicinesBody").innerHTML =
          `<tr><td colspan="5" class="text-center py-3 text-muted">No medicines found.</td></tr>`;
      }
    })
    .catch(function () {
      document.getElementById("recentMedicinesBody").innerHTML =
        `<tr><td colspan="5" class="text-center py-3 text-danger">Failed to load data. Please refresh.</td></tr>`;
    });
});
