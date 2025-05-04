// For USER settings
const CHARACTERS = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_";
const RANDOM_PASSWORD_LENGTH = 15;

function generateRandomPassword(length) {
  let result = ' ';
  for (let i = 0; i<length; i++){
    result += CHARACTERS.charAt(Math.floor(Math.random()* CHARACTERS.length))
  }
  return result;
}

function deleteUserRow(userId) {
  if (confirm("Are you sure you want to delete this user?")) {
    fetch(`/admin/user/${userId}`, {
      method: 'DELETE'
    })
        .then(response => {
          if (!response.ok) {
            throw new Error('Error while deleting the user.');
          }
          const button = document.querySelector(`button[onclick="deleteUserRow(${userId})"]`);
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

document.addEventListener("DOMContentLoaded", () => {
  const userTab = document.querySelector(".user-tab");
  if (!userTab) return; // Ensure this script only runs in the user tab

  const modal = userTab.querySelector("#userEditModal");
  const closeBtn = modal.querySelector(".close");
  const userForm = userTab.querySelector("#user-form");
  var roles = [];
  var roleString = "";
  var counter = 0;

  // editing a user
  userTab.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const row = btn.closest("tr");
      const cells = row.querySelectorAll("td");
      userId = cells[0].textContent.trim();
      const roleName = cells[1].textContent.trim().toLowerCase();

      userTab.querySelectorAll("input[name='role']").forEach((radio) => {
        radio.checked = radio.id === `role-${roleName}`;
      });
      userForm.action = `/admin/update-user/${userId}`
      document.getElementById("user-first-name").value = cells[2].textContent.trim();
      document.getElementById("user-first-name").required = false;

      document.getElementById("user-last-name").value = cells[3].textContent.trim();
      document.getElementById("user-last-name").required = false;

      document.getElementById("user-email").value = cells[4].textContent.trim();
      document.getElementById("user-email").required = false;

      document.getElementById("user-password").required = false;


      // // --- reset and extract role ---
      // roles = [];
      // roleString = "";
      // counter = 0;
      // const roleText = cells[1].textContent.trim();
      //
      // for (const char of roleText) {
      //   roleString += char;
      //   counter++;
      //   if (char === ',' || counter === roleText.length) {
      //     roles.push(roleString.replace(',', '').trim());
      //     roleString = "";
      //   }
      // }
      //
      // for (const role of roles) {
      //   switch (role) {
      //     case "Professor":
      //       userTab.querySelector("#select-role-prof").checked = true;
      //       break;
      //     case "Student":
      //       userTab.querySelector("#select-role-student").checked = true;
      //       break;
      //     case "Admin":
      //       userTab.querySelector("#is-admin").checked = true;
      //       break;
      //     default:
      //       console.log("No role could be read.");
      //   }
      // }

      userTab.querySelector("#user-modal-title").textContent = "Edit user";
      userTab.querySelector("#user-modal-submit").textContent = "Update";
      modal.style.display = "block";
    });
  });

  // --- adding a user ---
  const addBtn = userTab.querySelector(".add-btn");
  if (addBtn) {
    addBtn.addEventListener("click", () => {
      userForm.action = "/admin/add-user"

      document.getElementById("user-first-name").required = true;
      document.getElementById("user-last-name").required = true;
      document.getElementById("user-email").required = true;
      document.getElementById("user-password").required = true;

      userTab.querySelector("#user-first-name").value = "";
      userTab.querySelector("#user-last-name").value = "";
      userTab.querySelector("#user-email").value = "";
      userTab.querySelector("#user-password").value = generateRandomPassword(RANDOM_PASSWORD_LENGTH);
      userTab.querySelector("#user-modal-title").textContent = "Add a new user";
      userTab.querySelector("#user-modal-submit").textContent = "Add";
      modal.style.display = "block";
    });
  }

  // Close button script
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
  const userSearchInput = userTab.querySelector("#userSearchInput");
  if (userSearchInput) {
    userSearchInput.addEventListener("keyup", function () {
      const value = this.value.toLowerCase();
      const tableRows = userTab.querySelectorAll("#user-table tr");
      for (let i = 1; i < tableRows.length; i++) {
        const row = tableRows[i];
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
      }
    });
  }
});

// clear button & ensures student and admin can't be checked at the same time (just trying a bit of jquery 😎)

$(document).ready(() => {
  $("#select-role-student, #select-role-prof").on("change", () => {
    if ($("#select-role-student").is(":checked")) {
      $("#is-admin").prop("disabled", true);
      $("input[id=is-admin]").prop("checked", false);
    }
    else {
      $("#is-admin").prop("disabled", false);
    }
  });

  // clear button
  $("#clear-btn").on("click", () => {
    $("input[name=select_role]").prop("checked", false);
    $("#is-admin").prop("disabled", false);
  });

  // clear when clicked on add button 
  $(".add-btn").on("click", () => {
    $("input[name=select_role]").prop("checked", false);
  });
});



// DROPDOWN MENU

// $(document).ready(function () {
//   const tagBox = $('#tagBox');
//   const dropdown = $('#dropdown');
//   const hiddenInput = $('#selectedValues');
//   const ueSearchInput = $('#ueSearchInput');
//   let selected = [];
//   let allOptions = [];
  
//   // Store all dropdown options initially
//   dropdown.find('div').each(function() {
//     allOptions.push({
//       value: $(this).data('value'),
//       element: $(this).clone(true)
//     });
//   });
  
//   tagBox.on('click', function () {
//     dropdown.toggle();
//   });
  
//   // UE search functionality
//   ueSearchInput.on('keyup', function() {
//     const searchValue = $(this).val().toLowerCase();
    
//     // Clear dropdown
//     dropdown.empty();
    
//     // Filter and add matching options
//     allOptions.forEach(function(option) {
//       // Skip if already selected
//       if (selected.includes(option.value)) {
//         return;
//       }
      
//       if (option.value.toLowerCase().includes(searchValue)) {
//         dropdown.append(option.element.clone(true));
//       }
//     });
    
//     // Show dropdown when searching
//     if (searchValue.length > 0) {
//       dropdown.show();
//     }
//   });
  
//   // Prevent tagBox click event when clicking in search input
//   ueSearchInput.on('click', function(e) {
//     dropdown.show();
//     e.stopPropagation();
//   });
  
//   dropdown.on('click', 'div', function () {
//     const value = $(this).data('value');

//     if (!selected.includes(value)) {
//       selected.push(value);

//       // Add tag
//       const tag = $(`<span class="tag" data-value="${value}">${value}<span class="remove">&times;</span></span>`);
      
//       // Tag removal handler
//       tag.find('.remove').on('click', function (e) {
//         e.stopPropagation(); // Prevent opening dropdown when removing tag
//         const tagValue = $(this).parent().data('value');
//         $(this).parent().remove();
//         selected = selected.filter(v => v !== tagValue);
//         hiddenInput.val(selected.join(','));
        
//         // Find and add back the option from allOptions
//         const option = allOptions.find(o => o.value === tagValue);
//         if (option) {
//           dropdown.append(option.element.clone(true));
//         }
//       });

//       tagBox.append(tag);
//       hiddenInput.val(selected.join(','));

//       // Remove from dropdown
//       $(this).remove();
//     }

//     dropdown.hide();
//     ueSearchInput.val(''); // Clear search input after selection
//   });

//   // Hide dropdown when clicking outside
//   $(document).on('click', function (e) {
//     if (!$(e.target).closest('.multiselect-container').length) {
//       dropdown.hide();
//       ueSearchInput.val(''); // Clear search when closing
//     }
//   });
// });
