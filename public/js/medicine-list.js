const medicineTableBody = document.getElementById("medicineTableBody");

function formatDate(dateString) {
  if (!dateString) return "-";
  const date = new Date(dateString);
  return date.toLocaleDateString();
}

document.addEventListener("DOMContentLoaded", () => {
  const formData = new FormData();
  formData.append("token", window.currentUser.token);

  fetch(
    `/safedose/controller/medicine/all-medicine.php?token=${encodeURIComponent(window.currentUser.token)}`,
    {
      method: "GET",
    },
  )
    .then((r) => r.json())
    .then((data) => {
      medicineTableBody.innerHTML = "";

      if (data.success && data.data.length > 0) {
        data.data.forEach((med) => {
          const row = `
            <tr>
              <td>${med.id}</td>
              <td>${med.name}</td>
              <td>${med.category}</td>
              <td>${med.dosage}</td>
              <td>${med.dosage_strength}</td>
              <td>${med.quantity}</td>
              <td>${med.price}</td>
              <td>${formatDate(med.expiry_date)}</td>
              <td>${med.manufacturer ?? "-"}</td>
              <td>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-danger">Delete</button>
              </td>
            </tr>
          `;
          medicineTableBody.innerHTML += row;
        });
      } else {
        medicineTableBody.innerHTML = `
          <tr>
            <td colspan="10" class="text-center py-4 text-muted">
              No medicines found
            </td>
          </tr>
        `;
      }
    })
    .catch((err) => {
      console.log(err.message);
      medicineTableBody.innerHTML = `
        <tr>
          <td colspan="10" class="text-center py-4 text-danger">
            Failed to load medicines. Please refresh.
          </td>
        </tr>
      `;
    });
});
