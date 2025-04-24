function deleteUERow(){
  confirm("Are you sure you want to delete this UE ? \n(Will do nothing here, waiting for backend)");
}

document.addEventListener("DOMContentLoaded", () => {
  const ueTab = document.querySelector(".ue-tab");
  if (!ueTab) return; // Ensure this script only runs in the UE tab

  const modal = ueTab.querySelector("#ueEditModal"); // Updated to match the correct ID
  const closeBtn = modal.querySelector(".close");
  const settingsButtons = ueTab.querySelectorAll(".edit-btn");
  const rows = ueTab.querySelectorAll(".ue-list-container table tr");

  settingsButtons.forEach((btn, i) => {
    btn.addEventListener("click", () => {
      const row = rows[i + 1]; // skip header
      const cells = row.querySelectorAll("td");
      ueTab.querySelector("#ue-code").value = cells[0].textContent;
      ueTab.querySelector("#ue-name").value = cells[1].textContent;
      ueTab.querySelector("#ue-description").value = cells[2].textContent;
      ueTab.querySelector("#ue-credits").value = cells[3].textContent;
      ueTab.querySelector("#ue-modal-title").textContent = "UE settings";
      ueTab.querySelector("#ue-modal-submit").textContent = "Update";
      modal.style.display = "block";
    });
  });

  const addBtn = ueTab.querySelector(".add-btn");
  if (addBtn) {
    addBtn.addEventListener("click", () => {
      ueTab.querySelector("#ue-code").value = '';
      ueTab.querySelector("#ue-name").value = '';
      ueTab.querySelector("#ue-description").value = '';
      ueTab.querySelector("#ue-modal-title").textContent = "New UE";
      ueTab.querySelector("#ue-modal-submit").textContent = "Add";
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

  // Search bar
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
