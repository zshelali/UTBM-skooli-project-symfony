let initiallyAssignedUeCodes = new Set(); // will store initial assignments per selected user

document.addEventListener("DOMContentLoaded", () => {
    const assignationTab = document.querySelector(".assignation-tab");

    // USER SEARCH BAR -----------------------------------------------------------------------
    const assignationUserSearchInput = assignationTab.querySelector("#assignationUserSearchInput");
    const userTableRows = assignationTab.querySelectorAll("#assignation-user-table tr");

    if (assignationUserSearchInput) {
        // Hide all user rows initially (except the header)
        for (let i = 1; i < userTableRows.length; i++) {
            userTableRows[i].style.display = "none";
        }

        assignationUserSearchInput.addEventListener("keyup", function() {
            const value = this.value.toLowerCase();
            for (let i = 1; i < userTableRows.length; i++) {
                const row = userTableRows[i];
                const text = row.textContent.toLowerCase();
                row.style.display = value.trim() === "" ? "none" : (text.includes(value) ? "" : "none");
            }
        });
    }

    // UE SEARCH BAR -------------------------------------------------------------------------
    const assignationUESearchInput = assignationTab.querySelector("#assignationUESearchInput");

    function setupUESearch() {
        const ueTableRows = assignationTab.querySelectorAll("#assignation-available-ues tr");

        // Hide all initially
        for (let i = 0; i < ueTableRows.length; i++) {
            ueTableRows[i].style.display = "none";
        }

        if (assignationUESearchInput) {
            assignationUESearchInput.addEventListener("keyup", function () {
                const value = this.value.toLowerCase();
                const showAll = value.trim() !== "";

                for (let i = 0; i < ueTableRows.length; i++) {
                    const row = ueTableRows[i];
                    const text = row.textContent.toLowerCase();
                    row.style.display = showAll && text.includes(value) ? "" : "none";
                }
            });
        }
    }
    setupUESearch(); // refresh search behavior

    // USER SELECTION ------------------------------------------------------------------------
    const userRows = assignationTab.querySelectorAll(".assignation-user-rows");
    let userSelectionLocked = false;
    let ueSelectionLocked = true;
    const selectedUserIds = new Set();

    userRows.forEach(row => {
        row.addEventListener("click", () => {
            if (userSelectionLocked) {
                alert("You can't change selected users until you confirm or cancel the current operation.");
                return;
            }

            const userId = row.querySelector("td").textContent.trim();

            // Toggle visual + memory
            if (selectedUserIds.has(userId)) {
                selectedUserIds.delete(userId);
                row.classList.remove("selected");
            } else {
                selectedUserIds.add(userId);
                row.classList.add("selected");
            }

            ueSelectionLocked = selectedUserIds.size === 0;
            console.log("Selected users:", Array.from(selectedUserIds));

            // Fetch existing assignments from backend
            fetch('/admin/assignation/fetch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ userId })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const ueCodes = data.assignedUes;
                        initiallyAssignedUeCodes = new Set(ueCodes); // save initial state
                        const availableRows = Array.from(document.querySelectorAll("#assignation-available-ues tr"));

                        ueCodes.forEach(code => {
                            const match = availableRows.find(row => row.textContent.trim() === code);
                            if (match) {
                                const clone = match.cloneNode(true);
                                clone.classList.remove("selected", "pending-ue");
                                clone.style.display = "";
                                document.querySelector("#assignation-registered-ues").appendChild(clone);
                                match.remove();
                            }
                        });

                        setupUeSelection();
                        setupUESearch();
                    }
                });
        });
    });

    // UE SELECTION ---------------------------------------------------------------------------
    function setupUeSelection() {
        const ueRows = assignationTab.querySelectorAll("#assignation-available-ues tr, #assignation-registered-ues tr");

        ueRows.forEach(row => {
            const newRow = row.cloneNode(true);
            newRow.style.display = "";
            row.replaceWith(newRow);

            newRow.addEventListener("click", () => {
                if (ueSelectionLocked) {
                    alert("Please select at least one user before selecting UEs.");
                    return;
                }

                const ueCode = newRow.querySelector("td").textContent.trim();

                if (selectedUeCodes.has(ueCode)) {
                    selectedUeCodes.delete(ueCode);
                    newRow.classList.remove("selected");
                } else {
                    selectedUeCodes.add(ueCode);
                    newRow.classList.add("selected");
                }

                console.log("Selected UEs:", Array.from(selectedUeCodes));
            });
        });
    }

    setupUeSelection();
    setupUESearch();
    const selectedUeCodes = new Set();


    // ADD BUTTON LOGIC ----------------------------------------------------------------------
    const addButton = document.getElementById("assignation-add-btn");
    const availableUeTable = document.getElementById("assignation-available-ues");
    const registeredUeTable = document.getElementById("assignation-registered-ues");

    addButton.addEventListener("click", () => {
        if (selectedUserIds.size === 0 || selectedUeCodes.size === 0) {
            alert("Please select at least one user and one UE.");
            return;
        }

        userSelectionLocked = true;

        selectedUeCodes.forEach(ueCode => {
            const ueRow = Array.from(availableUeTable.querySelectorAll("tr")).find(row =>
                row.textContent.trim() === ueCode
            );

            if (ueRow) {
                ueRow.classList.remove("selected");
                ueRow.classList.add("pending-ue");
                const clone = ueRow.cloneNode(true);
                clone.classList.remove("selected");
                clone.classList.add("pending-ue");
                clone.style.display = "";
                registeredUeTable.appendChild(clone);
                ueRow.remove();
            }
        });

        selectedUeCodes.clear();
        setupUeSelection();
        setupUESearch();
    });

    // REMOVE BUTTON LOGIC

    const removeButton = document.getElementById("assignation-remove-btn");

    removeButton.addEventListener("click", () => {
        if (selectedUeCodes.size === 0) {
            alert("Please select at least one UE to remove.");
            return;
        }

        selectedUeCodes.forEach(ueCode => {
            const ueRow = Array.from(registeredUeTable.querySelectorAll("tr")).find(row =>
                row.textContent.trim() === ueCode
            );

            if (ueRow) {
                ueRow.classList.remove("selected", "pending-ue");
                availableUeTable.appendChild(ueRow);
            }
        });

        selectedUeCodes.clear();

        // Re-bind selection events for moved UEs
        setupUeSelection();
        setupUESearch();
    });

    //CONFIRM BUTTON LOGIC ----------------------------------------------------------
    const confirmButton = document.getElementById("assignation-confirm-btn");

    confirmButton.addEventListener("click", () => {
        if (selectedUserIds.size === 0) {
            alert("No users selected.");
            return;
        }

        const registeredRows = registeredUeTable.querySelectorAll("tr");

        const addedUeCodes = [];
        const confirmedUeCodes = [];

        registeredRows.forEach(row => {
            const code = row.querySelector("td").textContent.trim();
            if (row.classList.contains("pending-ue")) {
                addedUeCodes.push(code);
            } else {
                confirmedUeCodes.push(code);
            }
        });

        const currentRegisteredCodes = Array.from(registeredRows).map(row =>
            row.querySelector("td").textContent.trim()
        );

        const removedUeCodes = Array.from(initiallyAssignedUeCodes).filter(code => !currentRegisteredCodes.includes(code));

        if (addedUeCodes.length === 0 && removedUeCodes.length === 0) {
            alert("No changes to confirm.");
            return;
        }

        const payload = {
            userIds: Array.from(selectedUserIds),
            addedUeCodes,
            removedUeCodes
        };

        if (!confirm(`Are you sure you want to assign the selected UEs to ${selectedUserIds.size} user(s)?`)) {
            return;
        }

        console.log("Sending payload:", payload);

        fetch('/admin/assignation/confirm', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to update assignations.');
                }
                return response.json();
            })
            .then(data => {
                alert('Assignations updated successfully!');
                console.log('Server response:', data);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating assignations.');
            });
    });

});