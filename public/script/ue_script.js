document.addEventListener("DOMContentLoaded", () => {
  const ueTab = document.querySelector(".ue-tab");
  if (!ueTab) return;

  const modal = ueTab.querySelector("#ueEditModal");
  const closeBtn = modal.querySelector(".close");
  const settingsButtons = ueTab.querySelectorAll(".edit-btn");
  const rows = ueTab.querySelectorAll(".ue-list-container table tr");
  const ueForm = ueTab.querySelector("#ue-form");
  const modalTitle = ueTab.querySelector("#ue-modal-title");
  const modalSubmit = ueTab.querySelector("#ue-modal-submit");

  settingsButtons.forEach((btn, i) => {
    btn.addEventListener("click", () => {
      const row = rows[i + 1]; // Ignorer l'en-tête
      const cells = row.querySelectorAll("td");
      const ueId = cells[0].textContent.trim();
      const ueCode = cells[1].textContent.trim();
      const ueName = cells[2].textContent.trim();
      const ueDesc = cells[3].textContent.trim();
      const ueCredits = cells[4].textContent.trim();

      ueForm.action = `/admin/update-ue/${ueId}`; // dynamique
      ueForm.querySelector("#ue-code").value = ueCode;
      ueForm.querySelector("#ue-name").value = ueName;
      ueForm.querySelector("#ue-description").value = ueDesc;
      ueForm.querySelector("#ue-credits").value = ueCredits;

      modalTitle.textContent = "UE settings";
      modalSubmit.textContent = "Update";
      modal.style.display = "block";
    });
  });

  const addBtn = ueTab.querySelector(".add-btn");
  if (addBtn) {
    addBtn.addEventListener("click", () => {
      ueForm.action = "/admin/add-ue"; // statique
      ueForm.querySelector("#ue-code").value = '';
      ueForm.querySelector("#ue-name").value = '';
      ueForm.querySelector("#ue-description").value = '';
      ueForm.querySelector("#ue-credits").value = '';
      ueForm.querySelector("#ue-input-illustration").value = '';

      modalTitle.textContent = "New UE";
      modalSubmit.textContent = "Add";
      modal.style.display = "block";
    });
  }

  if (closeBtn) {
    closeBtn.onclick = () => {
      modal.style.display = "none";
    };
  }

  window.onclick = (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  };

  // Barre de recherche
  const ueSearchInput = ueTab.querySelector("#ueSearchInput");
  if (ueSearchInput) {
    ueSearchInput.addEventListener("keyup", function () {
      const value = this.value.toLowerCase();
      const tableRows = ueTab.querySelectorAll("#ueTable tr:not(:first-child):not(:last-child)");

      tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
      });
    });
  }
});

// Suppression d'UE (même principe que pour les utilisateurs)
function deleteUeRow(ueId) {
  if (confirm("Are you sure you want to delete this UE?")) {
    fetch(`/admin/ue/${ueId}`, {
      method: "DELETE"
    })
        .then(response => {
          if (!response.ok) {
            throw new Error('Error while deleting the UE.');
          }
          const button = document.querySelector(`button[onclick="deleteUeRow(${ueId})"]`);
          if (button) {
            const row = button.closest('tr');
            if (row) {
              row.remove();
            }
          }
        })
        .catch(error => {
          alert('An error occurred: ' + error.message);
        });
  }
}