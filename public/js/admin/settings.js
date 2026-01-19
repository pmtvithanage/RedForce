document.addEventListener("DOMContentLoaded", function () {
  console.log("=== Admin Settings JS Loaded ===");
  console.log("URL_ROOT:", typeof URL_ROOT !== 'undefined' ? URL_ROOT : 'NOT DEFINED');
  
  // DOM Elements
  const uploadArea = document.getElementById("uploadArea");
  const profilePhotoInput = document.getElementById("profilePhoto");
  const previewImage = document.getElementById("previewImage");
  const previewImg = document.getElementById("previewImg");
  const removePhotoBtn = document.getElementById("removePhoto");
  const createUserForm = document.getElementById("createUserForm");
  const viewButtons = document.querySelectorAll(".view-btn");
  const userRoleSelect = document.getElementById("userRole");
  const roleSpecificFields = document.getElementById("roleSpecificFields");
  const additionalFields = document.getElementById("additionalFields");

  console.log("=== DOM Elements Check ===");
  console.log("viewButtons found:", viewButtons.length);

  // Admin Form Elements (only if they exist)
  const adminUploadArea = document.getElementById("adminUploadArea");
  const adminProfilePhotoInput = document.getElementById("adminProfilePhoto");
  const adminPreviewImage = document.getElementById("adminPreviewImage");
  const adminPreviewImg = document.getElementById("adminPreviewImg");
  const adminRemovePhotoBtn = document.getElementById("adminRemovePhoto");
  const addAdminForm = document.getElementById("addAdminForm");
  const adminUserIDField = document.getElementById("adminUserID");

  // Load preview userID for admin form
  if (adminUserIDField) {
    console.log("Admin form found, fetching preview userID");
    fetchPreviewUserID('admin');
  }

  // ========== IMAGE UPLOAD FUNCTIONALITY ==========
  if (uploadArea && profilePhotoInput && previewImage && previewImg && removePhotoBtn) {
    uploadArea.addEventListener("click", function () {
      profilePhotoInput.click();
    });

    uploadArea.addEventListener("dragover", function (e) {
      e.preventDefault();
      uploadArea.style.borderColor = "#ff5252";
      uploadArea.style.background = "#ffe6e6";
    });

    uploadArea.addEventListener("dragleave", function (e) {
      e.preventDefault();
      uploadArea.style.borderColor = "#ff6b6b";
      uploadArea.style.background = "#fff5f5";
    });

    uploadArea.addEventListener("drop", function (e) {
      e.preventDefault();
      uploadArea.style.borderColor = "#ff6b6b";
      uploadArea.style.background = "#fff5f5";

      const files = e.dataTransfer.files;
      if (files.length > 0) {
        handleImageUpload(files[0]);
      }
    });

    profilePhotoInput.addEventListener("change", function (e) {
      if (e.target.files.length > 0) {
        handleImageUpload(e.target.files[0]);
      }
    });

    function handleImageUpload(file) {
      if (!file.type.startsWith("image/")) {
        showNotification("Please select an image file", "error");
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        previewImg.src = e.target.result;
        uploadArea.style.display = "none";
        previewImage.style.display = "block";
      };
      reader.readAsDataURL(file);
    }

    removePhotoBtn.addEventListener("click", function () {
      previewImage.style.display = "none";
      uploadArea.style.display = "flex";
      profilePhotoInput.value = "";
    });
  }

  // ========== ADMIN IMAGE UPLOAD FUNCTIONALITY ==========
  if (adminUploadArea && adminProfilePhotoInput && adminPreviewImage && adminPreviewImg && adminRemovePhotoBtn) {
    adminUploadArea.addEventListener("click", function () {
      adminProfilePhotoInput.click();
    });

    adminUploadArea.addEventListener("dragover", function (e) {
      e.preventDefault();
      adminUploadArea.style.borderColor = "#ff5252";
      adminUploadArea.style.background = "#ffe6e6";
    });

    adminUploadArea.addEventListener("dragleave", function (e) {
      e.preventDefault();
      adminUploadArea.style.borderColor = "#ff6b6b";
      adminUploadArea.style.background = "#fff5f5";
    });

    adminUploadArea.addEventListener("drop", function (e) {
      e.preventDefault();
      adminUploadArea.style.borderColor = "#ff6b6b";
      adminUploadArea.style.background = "#fff5f5";

      const files = e.dataTransfer.files;
      if (files.length > 0) {
        handleAdminImageUpload(files[0]);
      }
    });

    adminProfilePhotoInput.addEventListener("change", function (e) {
      if (e.target.files.length > 0) {
        handleAdminImageUpload(e.target.files[0]);
      }
    });

    function handleAdminImageUpload(file) {
      if (!file.type.startsWith("image/")) {
        showNotification("Please select an image file", "error");
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        adminPreviewImg.src = e.target.result;
        adminUploadArea.style.display = "none";
        adminPreviewImage.style.display = "block";
      };
      reader.readAsDataURL(file);
    }

    adminRemovePhotoBtn.addEventListener("click", function () {
      adminPreviewImage.style.display = "none";
      adminUploadArea.style.display = "flex";
      adminProfilePhotoInput.value = "";
    });
  }

  // ========== ROLE-SPECIFIC FIELDS ==========
  if (userRoleSelect) {
    userRoleSelect.addEventListener("change", function () {
      const role = this.value;
      updateRoleSpecificFields(role);
    });
  }

  function updateRoleSpecificFields(role) {
    if (!additionalFields) return;
    
    additionalFields.innerHTML = "";

    if (role === "mobile rider") {
      additionalFields.innerHTML = `
                <div class="input-group">
                    <input type="text" name="vehicle_type" placeholder="Vehicle Type" required>
                </div>
                <div class="input-group">
                    <input type="text" name="license_number" placeholder="License Number" required>
                </div>
            `;
      if (roleSpecificFields) roleSpecificFields.style.display = "block";
    } else if (role === "caretaker") {
      additionalFields.innerHTML = `
                <div class="input-group">
                    <input type="text" name="qualifications" placeholder="Qualifications">
                </div>
                <div class="input-group">
                    <input type="text" name="experience" placeholder="Experience (years)">
                </div>
            `;
      if (roleSpecificFields) roleSpecificFields.style.display = "block";
    } else if (role === "premise officer") {
      additionalFields.innerHTML = `
                <div class="input-group">
                    <input type="text" name="premise_id" placeholder="Premise ID">
                </div>
                <div class="input-group">
                    <select name="shift">
                        <option value="">Select Shift</option>
                        <option value="morning">Morning</option>
                        <option value="evening">Evening</option>
                        <option value="night">Night</option>
                    </select>
                </div>
            `;
      if (roleSpecificFields) roleSpecificFields.style.display = "block";
    } else {
      if (roleSpecificFields) roleSpecificFields.style.display = "none";
    }
  }

  // ========== VIEW BUTTON EVENT LISTENERS ==========
  console.log("=== Setting up View Button Listeners ===");
  viewButtons.forEach((button, index) => {
    console.log(`Attaching listener to button ${index}`);
    
    button.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("View button clicked!");
      
      const userID = this.getAttribute("data-userid");
      console.log("UserID from button:", userID);
      
      if (!userID) {
        console.error("No userID found on button!");
        showNotification("Error: Admin ID not found", "error");
        return;
      }
      
      showAdminDetails(userID);
    });
  });

  function showAdminDetails(userID) {
    console.log("=== showAdminDetails called with userID:", userID);
    
    if (typeof URL_ROOT === 'undefined') {
      console.error("ERROR: URL_ROOT is not defined!");
      showNotification("Configuration error: URL_ROOT not defined", "error");
      return;
    }
    
    const fetchUrl = `${URL_ROOT}/admin/getUserDetails/${userID}`;
    console.log("Fetching from URL:", fetchUrl);
    
    fetch(fetchUrl)
      .then((response) => {
        console.log("Response received, status:", response.status);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        console.log("Data received:", data);
        if (data.success) {
          console.log("Success! Creating modal with data:", data.user);
          createAdminModal(data.user);
        } else {
          console.error("API returned success: false");
          showNotification("Failed to load admin details: " + (data.message || "Unknown error"), "error");
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        showNotification("Error loading admin details: " + error.message, "error");
      });
  }

  function createAdminModal(admin) {
    console.log("=== createAdminModal called with admin:", admin);
    
    const modal = document.createElement("div");
    modal.className = "modal";

    let additionalInfo = {};
    try {
      additionalInfo = admin.additional_info
        ? JSON.parse(admin.additional_info)
        : {};
    } catch (e) {
      console.error("Error parsing additional info:", e);
    }

    const profileImage = admin.profile_image && admin.profile_image.trim() !== '' 
      ? `${URL_ROOT}/${admin.profile_image}`
      : "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=face";

    console.log("Profile image URL:", profileImage);

    modal.innerHTML = `
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-header">
                    <h2>Admin Profile</h2>
                    <button class="edit-toggle-btn" id="editToggleBtn">
                        <span class="edit-icon">✏️</span>
                        <span class="edit-text">Edit</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="profile-section">
                        <div class="profile-photo-container">
                            <div class="profile-photo">
                                <img src="${profileImage}" alt="${admin.name}" id="profileImage">
                            </div>
                            <div class="photo-upload-overlay" id="photoUploadOverlay" style="display: none;">
                                <input type="file" id="profilePhotoUpload" accept="image/*" hidden>
                                <span>Change Photo</span>
                            </div>
                        </div>
                        <h2 class="user-name" id="userNameDisplay">${admin.name}</h2>
                        <div class="contact-details">
                            <div class="contact-item">
                                <strong>User ID:</strong> 
                                <span class="contact-value" id="userIDDisplay">${admin.userID}</span>
                            </div>
                            <div class="contact-item">
                                <strong>Name:</strong> 
                                <span class="contact-value" id="nameDisplay">${admin.name}</span>
                                <input type="text" class="contact-input" id="nameInput" value="${admin.name}" style="display: none;" placeholder="Full Name">
                            </div>
                            <div class="contact-item">
                                <strong>NIC:</strong> 
                                <span class="contact-value" id="nicDisplay">${admin.nic || "N/A"}</span>
                                <input type="text" class="contact-input" id="nicInput" value="${admin.nic || ""}" style="display: none;" placeholder="NIC">
                            </div>
                            <div class="contact-item">
                                <strong>Email:</strong> 
                                <span class="contact-value" id="emailDisplay">${admin.email}</span>
                                <input type="email" class="contact-input" id="emailInput" value="${admin.email}" style="display: none;" placeholder="Email">
                            </div>
                            <div class="contact-item">
                                <strong>Mobile No:</strong> 
                                <span class="contact-value" id="mobileDisplay">${admin.mobile || "N/A"}</span>
                                <input type="tel" class="contact-input" id="mobileInput" value="${admin.mobile || ""}" style="display: none;" placeholder="Mobile Number">
                            </div>
                            <div class="contact-item">
                                <strong>Address:</strong> 
                                <span class="contact-value" id="addressDisplay">${admin.address || "N/A"}</span>
                                <input type="text" class="contact-input" id="addressInput" value="${admin.address || ""}" style="display: none;" placeholder="Address">
                            </div>
                            <div class="contact-item">
                                <strong>Status:</strong> 
                                <span class="contact-value status-${admin.status}">${admin.status}</span>
                                <select class="contact-input" id="statusInput" style="display: none;">
                                    <option value="active" ${admin.status === "active" ? "selected" : ""}>Active</option>
                                    <option value="inactive" ${admin.status === "inactive" ? "selected" : ""}>Inactive</option>
                                    <option value="suspended" ${admin.status === "suspended" ? "selected" : ""}>Suspended</option>
                                </select>
                            </div>
                            <div class="contact-item">
                                <strong>Created Date:</strong> 
                                <span class="contact-value">${new Date(admin.created_at).toLocaleDateString()}</span>
                            </div>
                            <div class="permissions-display" id="permissionsDisplay" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
                                <strong style="display: block; margin-bottom: 10px;">Permissions:</strong>
                                <div id="permissionsList" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button class="save-btn" id="saveBtn" style="display: none;">Save Changes</button>
                    <button class="cancel-btn" id="cancelBtn" style="display: none;">Cancel</button>
                    <button class="remove-btn" id="removeBtn">Remove Admin</button>
                    <button class="close-btn" id="closeBtn">Close</button>
                </div>
            </div>
        `;

    document.body.appendChild(modal);
    console.log("Modal appended to DOM");

    const editToggleBtn = modal.querySelector("#editToggleBtn");
    const saveBtn = modal.querySelector("#saveBtn");
    const cancelBtn = modal.querySelector("#cancelBtn");
    const removeBtn = modal.querySelector("#removeBtn");
    const closeBtn = modal.querySelector("#closeBtn");
    const photoUploadOverlay = modal.querySelector("#photoUploadOverlay");
    const profilePhotoUpload = modal.querySelector("#profilePhotoUpload");
    const permissionsList = modal.querySelector("#permissionsList");

    console.log("Loading permissions...");
    loadAdminPermissions(admin.userID, permissionsList);

    const originalValues = {
      name: admin.name,
      nic: admin.nic || "",
      email: admin.email,
      mobile: admin.mobile || "",
      address: admin.address || "",
      status: admin.status,
    };

    let isEditing = false;

    editToggleBtn.addEventListener("click", function () {
      console.log("Edit button clicked, isEditing:", isEditing);
      isEditing = !isEditing;

      if (isEditing) {
        console.log("Entering edit mode");
        editToggleBtn.classList.add("editing");
        editToggleBtn.querySelector(".edit-text").textContent = "Cancel Edit";
        editToggleBtn.querySelector(".edit-icon").textContent = "✖";

        modal.querySelectorAll(".contact-value").forEach((span) => (span.style.display = "none"));
        modal.querySelectorAll(".contact-input, #statusInput").forEach((input) => (input.style.display = "block"));
        photoUploadOverlay.style.display = "flex";

        saveBtn.style.display = "block";
        cancelBtn.style.display = "block";
        closeBtn.style.display = "none";
        removeBtn.style.display = "none";
      } else {
        console.log("Exiting edit mode");
        exitEditMode();
      }
    });

    saveBtn.addEventListener("click", function () {
      console.log("Save button clicked");
      const name = modal.querySelector("#nameInput").value.trim();
      const email = modal.querySelector("#emailInput").value.trim();
      const mobile = modal.querySelector("#mobileInput").value.trim();
      const nic = modal.querySelector("#nicInput").value.trim();
      const address = modal.querySelector("#addressInput").value.trim();
      const status = modal.querySelector("#statusInput").value;

      if (!name) {
        showNotification("Please enter name", "error");
        return;
      }

      if (!email) {
        showNotification("Please enter email", "error");
        return;
      }

      if (!isValidEmail(email)) {
        showNotification("Please enter a valid email", "error");
        return;
      }

      if (mobile && !isValidMobile(mobile)) {
        showNotification("Please enter a valid mobile number (10-15 digits)", "error");
        return;
      }

      const form = document.createElement("form");
      form.method = "POST";
      form.action = `${URL_ROOT}/admin/updateAdminProfile/${admin.userID}`;
      form.style.display = "none";

      const fields = {
        name: name,
        email: email,
        nic: nic,
        mobile: mobile,
        address: address,
        status: status
      };

      for (const [key, value] of Object.entries(fields)) {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = key;
        input.value = value;
        form.appendChild(input);
      }

      document.body.appendChild(form);
      console.log("Submitting form to:", form.action);
      form.submit();
    });

    cancelBtn.addEventListener("click", function () {
      console.log("Cancel button clicked");
      modal.querySelector("#nameInput").value = originalValues.name;
      modal.querySelector("#nicInput").value = originalValues.nic;
      modal.querySelector("#emailInput").value = originalValues.email;
      modal.querySelector("#mobileInput").value = originalValues.mobile;
      modal.querySelector("#addressInput").value = originalValues.address;
      modal.querySelector("#statusInput").value = originalValues.status;

      exitEditMode();
    });

    function exitEditMode() {
      isEditing = false;

      editToggleBtn.classList.remove("editing");
      editToggleBtn.querySelector(".edit-text").textContent = "Edit";
      editToggleBtn.querySelector(".edit-icon").textContent = "✏️";

      modal.querySelectorAll(".contact-value").forEach((span) => (span.style.display = "block"));
      modal.querySelectorAll(".contact-input, #statusInput").forEach((input) => (input.style.display = "none"));
      photoUploadOverlay.style.display = "none";

      saveBtn.style.display = "none";
      cancelBtn.style.display = "none";
      removeBtn.style.display = "block";
      closeBtn.style.display = "block";
    }

    photoUploadOverlay.addEventListener("click", function () {
      profilePhotoUpload.click();
    });

    profilePhotoUpload.addEventListener("change", function (e) {
      if (e.target.files.length > 0) {
        const file = e.target.files[0];
        if (!file.type.startsWith("image/")) {
          showNotification("Please select an image file", "error");
          return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
          modal.querySelector("#profileImage").src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });

    removeBtn.addEventListener("click", function () {
      console.log("Remove button clicked");
      showAdminConfirmationModal(admin.name, modal, admin.userID);
    });

    closeBtn.addEventListener("click", function () {
      console.log("Close button clicked");
      document.body.removeChild(modal);
    });

    modal.querySelector(".close").addEventListener("click", function () {
      console.log("X button clicked");
      document.body.removeChild(modal);
    });

    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        console.log("Outside modal clicked");
        document.body.removeChild(modal);
      }
    });
  }

  function loadAdminPermissions(userID, permissionsList) {
    console.log("loadAdminPermissions called with userID:", userID);
    
    if (typeof URL_ROOT === 'undefined') {
      console.error("URL_ROOT not defined in loadAdminPermissions");
      return;
    }
    
    const fetchUrl = `${URL_ROOT}/admin/getAdminPermissions/${userID}`;
    console.log("Fetching permissions from:", fetchUrl);
    
    fetch(fetchUrl)
      .then((response) => {
        console.log("Permissions response status:", response.status);
        return response.json();
      })
      .then((data) => {
        console.log("Permissions data:", data);
        if (data.success && data.permissions) {
          const permissions = data.permissions;
          if (permissions.length > 0) {
            permissionsList.innerHTML = permissions
              .map(
                (perm) =>
                  `<div class="permission-badge">
                    <span class="permission-icon">✓</span>
                    ${formatPermissionName(perm.permission)}
                  </div>`
              )
              .join("");
          } else {
            permissionsList.innerHTML = '<span style="grid-column: 1/-1; color: #999;">No permissions assigned</span>';
          }
        } else {
          console.warn("Permissions fetch not successful:", data);
          permissionsList.innerHTML = '<span style="grid-column: 1/-1; color: #999;">Unable to load permissions</span>';
        }
      })
      .catch((error) => {
        console.error("Error loading permissions:", error);
        permissionsList.innerHTML = '<span style="grid-column: 1/-1; color: #999;">Unable to load permissions</span>';
      });
  }

  function formatPermissionName(permission) {
    return permission
      .split("_")
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
      .join(" ");
  }

  function showAdminConfirmationModal(adminName, parentModal, userID) {
    console.log("showAdminConfirmationModal called for:", adminName, userID);
    
    const confirmationModal = document.createElement("div");
    confirmationModal.className = "confirmation-modal";
    confirmationModal.innerHTML = `
            <div class="confirmation-content">
                <div class="confirmation-header">
                    <h3>⚠️ Remove Admin</h3>
                </div>
                <div class="confirmation-body">
                    <p>Are you sure you want to remove <strong>${adminName}</strong>?</p>
                    <p class="warning-text">This action cannot be undone.</p>
                </div>
                <div class="confirmation-actions">
                    <button class="confirm-remove-btn">Remove Admin</button>
                    <button class="cancel-remove-btn">Cancel</button>
                </div>
            </div>
        `;

    document.body.appendChild(confirmationModal);

    const confirmBtn = confirmationModal.querySelector(".confirm-remove-btn");
    const cancelBtn = confirmationModal.querySelector(".cancel-remove-btn");

    confirmBtn.addEventListener("click", function () {
      console.log("Confirm delete clicked for userID:", userID);
      
      if (typeof URL_ROOT === 'undefined') {
        console.error("URL_ROOT not defined in delete");
        showNotification("Configuration error", "error");
        return;
      }
      
      const deleteUrl = `${URL_ROOT}/admin/deleteUser/${userID}`;
      console.log("Deleting from:", deleteUrl);
      
      fetch(deleteUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((response) => {
          console.log("Delete response status:", response.status);
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.text();
        })
        .then((text) => {
          console.log("Delete response text:", text);
          try {
            return JSON.parse(text);
          } catch (e) {
            console.error("Failed to parse JSON:", e);
            throw new Error("Invalid JSON response");
          }
        })
        .then((data) => {
          console.log("Delete response data:", data);
          if (data.success) {
            try {
              if (confirmationModal.parentNode) {
                document.body.removeChild(confirmationModal);
              }
            } catch (e) {
              console.log("Confirmation modal already removed");
            }
            
            try {
              if (parentModal.parentNode) {
                document.body.removeChild(parentModal);
              }
            } catch (e) {
              console.log("Parent modal already removed");
            }

            showNotification(`${adminName} has been removed successfully`, "success");

            setTimeout(() => {
              location.reload();
            }, 2000);
          } else {
            showNotification(data.message || "Failed to remove admin", "error");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showNotification("Error removing admin: " + error.message, "error");
        });
    });

    cancelBtn.addEventListener("click", function () {
      console.log("Cancel delete clicked");
      document.body.removeChild(confirmationModal);
    });

    confirmationModal.addEventListener("click", function (e) {
      if (e.target === confirmationModal) {
        document.body.removeChild(confirmationModal);
      }
    });
  }

  if (createUserForm) {
    createUserForm.addEventListener("submit", function (e) {
      e.preventDefault();

      clearErrors();

      const formData = new FormData(this);

      const submitBtn = createUserForm.querySelector(".create-btn");
      const originalText = submitBtn.textContent;
      submitBtn.textContent = "Creating...";
      submitBtn.disabled = true;

      fetch(this.action, {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          console.log("Response status:", response.status);
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.text();
        })
        .then((text) => {
          console.log("Raw response:", text);
          try {
            return JSON.parse(text);
          } catch (e) {
            console.error("Failed to parse JSON:", e);
            console.error("Response text:", text);
            throw new Error("Invalid JSON response from server");
          }
        })
        .then((data) => {
          console.log("Parsed data:", data);
          if (data.success) {
            showSuccessMessage();
            createUserForm.reset();
            previewImage.style.display = "none";
            uploadArea.style.display = "flex";
            if (roleSpecificFields) roleSpecificFields.style.display = "none";

            setTimeout(() => {
              location.reload();
            }, 2000);
          } else {
            if (data.errors) {
              displayErrors(data.errors);
            } else if (data.message) {
              showNotification(data.message, "error");
            } else {
              showNotification("Error creating user", "error");
            }
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showNotification("Error creating user: " + error.message, "error");
        })
        .finally(() => {
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
        });
    });
  }

  if (addAdminForm) {
    addAdminForm.addEventListener("submit", function (e) {
      e.preventDefault();

      clearAdminErrors();

      const formData = new FormData(this);

      const submitBtn = addAdminForm.querySelector(".create-btn");
      const originalText = submitBtn.textContent;
      submitBtn.textContent = "Creating...";
      submitBtn.disabled = true;

      fetch(this.action, {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          console.log("Admin Response status:", response.status);
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.text();
        })
        .then((text) => {
          console.log("Raw admin response:", text);
          try {
            return JSON.parse(text);
          } catch (e) {
            console.error("Failed to parse JSON:", e);
            console.error("Response text:", text);
            throw new Error("Invalid JSON response from server");
          }
        })
        .then((data) => {
          console.log("Parsed admin data:", data);
          
          if (data.success) {
            console.log("SUCCESS: Showing popup...");
            showAdminSuccessPopup();
            
            addAdminForm.reset();
            if (adminPreviewImage) adminPreviewImage.style.display = "none";
            if (adminUploadArea) adminUploadArea.style.display = "flex";
            
            setTimeout(() => {
              fetchPreviewUserID('admin');
            }, 500);
          } else {
            console.log("FAILED: Success is false or not true");
            if (data.errors) {
              console.log("Errors found:", data.errors);
              displayAdminErrors(data.errors);
            } else if (data.message) {
              console.log("Message:", data.message);
              showNotification(data.message, "error");
            } else {
              console.log("No errors or message");
              showNotification("Error creating admin", "error");
            }
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showNotification("Error creating admin: " + error.message, "error");
        })
        .finally(() => {
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
        });
    });
  }

  function clearErrors() {
    const errorElements = document.querySelectorAll(".error-message");
    errorElements.forEach((el) => {
      el.textContent = "";
    });
  }

  function clearAdminErrors() {
    const adminErrorElements = document.querySelectorAll("#addAdminForm .error-message");
    adminErrorElements.forEach((el) => {
      el.textContent = "";
    });
  }

  function displayErrors(errors) {
    for (const [field, message] of Object.entries(errors)) {
      const errorElement = document.getElementById(field + "Error");
      if (errorElement && message) {
        errorElement.textContent = message;
      }
    }
  }

  function displayAdminErrors(errors) {
    for (const [field, message] of Object.entries(errors)) {
      const errorElement = document.getElementById("admin" + field.charAt(0).toUpperCase() + field.slice(1) + "Error");
      if (errorElement && message) {
        errorElement.textContent = message;
      }
    }
  }

  function showSuccessMessage() {
    const successMessage = document.getElementById("successMessage");
    if (successMessage) {
      successMessage.style.display = "block";

      setTimeout(() => {
        successMessage.style.display = "none";
      }, 5000);
    }
  }

  function showAdminSuccessPopup() {
    const overlay = document.createElement("div");
    overlay.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 10000;
    `;

    const popup = document.createElement("div");
    popup.style.cssText = `
      background: white;
      padding: 30px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      max-width: 400px;
      width: 90%;
    `;

    const icon = document.createElement("div");
    icon.innerHTML = "✅";
    icon.style.cssText = `
      font-size: 48px;
      margin-bottom: 15px;
    `;

    const message = document.createElement("div");
    message.textContent = "New Admin Added Successfully!";
    message.style.cssText = `
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 10px;
    `;

    const subMessage = document.createElement("div");
    subMessage.textContent = "The admin has been created and added to the system.";
    subMessage.style.cssText = `
      font-size: 14px;
      color: #666;
      margin-bottom: 20px;
    `;

    const okButton = document.createElement("button");
    okButton.textContent = "OK";
    okButton.style.cssText = `
      background-color: #a40000;
      color: white;
      border: none;
      padding: 10px 30px;
      border-radius: 5px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    `;

    okButton.addEventListener("mouseover", () => {
      okButton.style.backgroundColor = "#710000";
    });

    okButton.addEventListener("mouseout", () => {
      okButton.style.backgroundColor = "#a40000";
    });

    okButton.addEventListener("click", () => {
      if (overlay.parentNode) {
        overlay.parentNode.removeChild(overlay);
      }
      location.reload();
    });

    popup.appendChild(icon);
    popup.appendChild(message);
    popup.appendChild(subMessage);
    popup.appendChild(okButton);
    overlay.appendChild(popup);
    document.body.appendChild(overlay);
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function isValidMobile(mobile) {
    const cleanedMobile = mobile.replace(/\s/g, "");
    const mobileRegex = /^(\+)?[0-9]{10,15}$/;
    return mobileRegex.test(cleanedMobile);
  }

  function showNotification(message, type = "info") {
    console.log("showNotification:", type, message);
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
      if (notification.parentNode) {
        notification.remove();
      }
    }, 3000);
  }

  function fetchPreviewUserID(role) {
    console.log("Fetching preview userID for role:", role);
    fetch(`${URL_ROOT}/admin/getPreviewUserID/${role}`)
      .then(response => response.json())
      .then(data => {
        console.log("Preview userID response:", data);
        if (data.success && adminUserIDField) {
          adminUserIDField.value = data.userID;
        }
      })
      .catch(error => {
        console.error('Error fetching preview userID:', error);
      });
  }
});