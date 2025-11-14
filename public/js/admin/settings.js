document.addEventListener("DOMContentLoaded", function () {
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

  // Image Upload Functionality
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

  // Role-specific fields
  userRoleSelect.addEventListener("change", function () {
    const role = this.value;
    updateRoleSpecificFields(role);
  });

  function updateRoleSpecificFields(role) {
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
      roleSpecificFields.style.display = "block";
    } else if (role === "caretaker") {
      additionalFields.innerHTML = `
                <div class="input-group">
                    <input type="text" name="qualifications" placeholder="Qualifications">
                </div>
                <div class="input-group">
                    <input type="text" name="experience" placeholder="Experience (years)">
                </div>
            `;
      roleSpecificFields.style.display = "block";
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
      roleSpecificFields.style.display = "block";
    } else {
      roleSpecificFields.style.display = "none";
    }
  }

  // View Button Functionality
  viewButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const userID = this.getAttribute("data-userid");
      showUserDetails(userID);
    });
  });

  function showUserDetails(userID) {
    // Fetch user details via AJAX
    fetch(`${URL_ROOT}/admin/getUserDetails/${userID}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          createUserModal(data.user);
        } else {
          showNotification("Failed to load user details", "error");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification("Error loading user details", "error");
      });
  }

  function createUserModal(user) {
    const modal = document.createElement("div");
    modal.className = "modal";

    // Parse additional_info if it exists
    let additionalInfo = {};
    try {
      additionalInfo = user.additional_info
        ? JSON.parse(user.additional_info)
        : {};
    } catch (e) {
      console.error("Error parsing additional info:", e);
    }

    modal.innerHTML = `
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-header">
                    <h2>User Profile - ${user.role}</h2>
                    <button class="edit-toggle-btn" id="editToggleBtn">
                        <span class="edit-icon">✏️</span>
                        <span class="edit-text">Edit</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="profile-section">
                        <div class="profile-photo-container">
                            <div class="profile-photo">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop&crop=face" alt="${
                                  user.name
                                }" id="profileImage">
                            </div>
                            <div class="photo-upload-overlay" id="photoUploadOverlay" style="display: none;">
                                <input type="file" id="profilePhotoUpload" accept="image/*" hidden>
                                <span>Change Photo</span>
                            </div>
                        </div>
                        <h2 class="user-name" id="userNameDisplay">${
                          user.name
                        }</h2>
                        <div class="contact-details">
                            <div class="contact-item">
                                <strong>User ID:</strong> 
                                <span class="contact-value" id="userIDDisplay">${
                                  user.userID
                                }</span>
                            </div>
                            <div class="contact-item">
                                <strong>NIC:</strong> 
                                <span class="contact-value" id="nicDisplay">${
                                  user.nic || "N/A"
                                }</span>
                                <input type="text" class="contact-input" id="nicInput" value="${
                                  user.nic || ""
                                }" style="display: none;">
                            </div>
                            <div class="contact-item">
                                <strong>Email:</strong> 
                                <span class="contact-value" id="emailDisplay">${
                                  user.email
                                }</span>
                                <input type="email" class="contact-input" id="emailInput" value="${
                                  user.email
                                }" style="display: none;">
                            </div>
                            <div class="contact-item">
                                <strong>Mobile No:</strong> 
                                <span class="contact-value" id="mobileDisplay">${
                                  user.mobile || "N/A"
                                }</span>
                                <input type="tel" class="contact-input" id="mobileInput" value="${
                                  user.mobile || ""
                                }" style="display: none;">
                            </div>
                            <div class="contact-item">
                                <strong>Address:</strong> 
                                <span class="contact-value" id="addressDisplay">${
                                  user.address || "N/A"
                                }</span>
                                <input type="text" class="contact-input" id="addressInput" value="${
                                  user.address || ""
                                }" style="display: none;">
                            </div>
                            <div class="contact-item">
                                <strong>Status:</strong> 
                                <span class="contact-value status-${
                                  user.status
                                }">${user.status}</span>
                                <select class="contact-input" id="statusInput" style="display: none;">
                                    <option value="active" ${
                                      user.status === "active" ? "selected" : ""
                                    }>Active</option>
                                    <option value="inactive" ${
                                      user.status === "inactive"
                                        ? "selected"
                                        : ""
                                    }>Inactive</option>
                                    <option value="suspended" ${
                                      user.status === "suspended"
                                        ? "selected"
                                        : ""
                                    }>Suspended</option>
                                </select>
                            </div>
                            ${
                              user.role === "mobile rider"
                                ? `
                                <div class="contact-item">
                                    <strong>Vehicle Type:</strong> 
                                    <span class="contact-value">${
                                      additionalInfo.vehicle_type || "N/A"
                                    }</span>
                                </div>
                                <div class="contact-item">
                                    <strong>License Number:</strong> 
                                    <span class="contact-value">${
                                      additionalInfo.license_number || "N/A"
                                    }</span>
                                </div>
                            `
                                : ""
                            }
                            ${
                              user.role === "caretaker"
                                ? `
                                <div class="contact-item">
                                    <strong>Qualifications:</strong> 
                                    <span class="contact-value">${
                                      additionalInfo.qualifications || "N/A"
                                    }</span>
                                </div>
                                <div class="contact-item">
                                    <strong>Experience:</strong> 
                                    <span class="contact-value">${
                                      additionalInfo.experience || "N/A"
                                    }</span>
                                </div>
                            `
                                : ""
                            }
                            ${
                              user.role === "premise officer"
                                ? `
                                <div class="contact-item">
                                    <strong>Premise ID:</strong> 
                                    <span class="contact-value">${
                                      additionalInfo.premise_id || "N/A"
                                    }</span>
                                </div>
                                <div class="contact-item">
                                    <strong>Shift:</strong> 
                                    <span class="contact-value">${
                                      additionalInfo.shift || "N/A"
                                    }</span>
                                </div>
                            `
                                : ""
                            }
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button class="save-btn" id="saveBtn" style="display: none;">Save Changes</button>
                    <button class="cancel-btn" id="cancelBtn" style="display: none;">Cancel</button>
                    <button class="remove-btn" id="removeBtn">Remove User</button>
                    <button class="close-btn" id="closeBtn">Close</button>
                </div>
            </div>
        `;

    document.body.appendChild(modal);

    // Get modal elements
    const editToggleBtn = modal.querySelector("#editToggleBtn");
    const saveBtn = modal.querySelector("#saveBtn");
    const cancelBtn = modal.querySelector("#cancelBtn");
    const removeBtn = modal.querySelector("#removeBtn");
    const closeBtn = modal.querySelector("#closeBtn");
    const photoUploadOverlay = modal.querySelector("#photoUploadOverlay");
    const profilePhotoUpload = modal.querySelector("#profilePhotoUpload");

    // Store original values for cancel functionality
    const originalValues = {
      nic: user.nic || "",
      email: user.email,
      mobile: user.mobile || "",
      address: user.address || "",
      status: user.status,
    };

    let isEditing = false;

    // Edit toggle functionality
    editToggleBtn.addEventListener("click", function () {
      isEditing = !isEditing;

      if (isEditing) {
        // Enter edit mode
        editToggleBtn.classList.add("editing");
        editToggleBtn.querySelector(".edit-text").textContent = "Cancel Edit";
        editToggleBtn.querySelector(".edit-icon").textContent = "❌";

        // Show edit elements
        modal
          .querySelectorAll(".contact-value")
          .forEach((span) => (span.style.display = "none"));
        modal
          .querySelectorAll(".contact-input, #statusInput")
          .forEach((input) => (input.style.display = "block"));
        photoUploadOverlay.style.display = "flex";

        // Show save/cancel buttons
        saveBtn.style.display = "block";
        cancelBtn.style.display = "block";
        closeBtn.style.display = "none";
        removeBtn.style.display = "none";
      } else {
        // Exit edit mode
        exitEditMode();
      }
    });

    // Save functionality
    saveBtn.addEventListener("click", function () {
      // Validate inputs
      const email = modal.querySelector("#emailInput").value.trim();
      const mobile = modal.querySelector("#mobileInput").value.trim();

      if (!email) {
        showNotification("Please enter email", "error");
        return;
      }

      if (!isValidEmail(email)) {
        showNotification("Please enter a valid email", "error");
        return;
      }

      if (mobile && !isValidMobile(mobile)) {
        showNotification("Please enter a valid mobile number", "error");
        return;
      }

      // Update display values
      modal.querySelector("#nicDisplay").textContent =
        modal.querySelector("#nicInput").value || "N/A";
      modal.querySelector("#emailDisplay").textContent = email;
      modal.querySelector("#mobileDisplay").textContent =
        modal.querySelector("#mobileInput").value || "N/A";
      modal.querySelector("#addressDisplay").textContent =
        modal.querySelector("#addressInput").value || "N/A";

      const newStatus = modal.querySelector("#statusInput").value;
      modal.querySelector("#statusDisplay").textContent = newStatus;
      modal.querySelector(
        "#statusDisplay"
      ).className = `contact-value status-${newStatus}`;

      // Exit edit mode
      exitEditMode();

      // Show success message
      showNotification("User profile updated successfully!", "success");

      // TODO: Send update to server via AJAX
      // updateUserOnServer(user.userID, updatedData);
    });

    // Cancel functionality
    cancelBtn.addEventListener("click", function () {
      // Restore original values
      modal.querySelector("#nicInput").value = originalValues.nic;
      modal.querySelector("#emailInput").value = originalValues.email;
      modal.querySelector("#mobileInput").value = originalValues.mobile;
      modal.querySelector("#addressInput").value = originalValues.address;
      modal.querySelector("#statusInput").value = originalValues.status;

      exitEditMode();
    });

    function exitEditMode() {
      isEditing = false;

      // Reset edit button
      editToggleBtn.classList.remove("editing");
      editToggleBtn.querySelector(".edit-text").textContent = "Edit";
      editToggleBtn.querySelector(".edit-icon").textContent = "✏️";

      // Hide edit elements
      modal
        .querySelectorAll(".contact-value")
        .forEach((span) => (span.style.display = "block"));
      modal
        .querySelectorAll(".contact-input, #statusInput")
        .forEach((input) => (input.style.display = "none"));
      photoUploadOverlay.style.display = "none";

      // Show close button
      saveBtn.style.display = "none";
      cancelBtn.style.display = "none";
      removeBtn.style.display = "block";
      closeBtn.style.display = "block";
    }

    // Photo upload functionality
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

    // Remove user functionality
    removeBtn.addEventListener("click", function () {
      showConfirmationModal(user.name, modal, user.userID);
    });

    // Close modal functionality
    closeBtn.addEventListener("click", function () {
      document.body.removeChild(modal);
    });

    modal.querySelector(".close").addEventListener("click", function () {
      document.body.removeChild(modal);
    });

    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        document.body.removeChild(modal);
      }
    });

    // Add modal styles
    addModalStyles();
  }

  // Form Submission
  createUserForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Clear previous errors
    clearErrors();

    // Get form data
    const formData = new FormData(this);

    // Show loading state
    const submitBtn = createUserForm.querySelector(".create-btn");
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Creating...";
    submitBtn.disabled = true;

    // Submit via AJAX
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
          roleSpecificFields.style.display = "none";

          // Refresh page to show new user
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

  function clearErrors() {
    document.querySelectorAll(".error-message").forEach((el) => {
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

  function showSuccessMessage() {
    const successMessage = document.getElementById("successMessage");
    successMessage.style.display = "block";

    setTimeout(() => {
      successMessage.style.display = "none";
    }, 5000);
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function isValidMobile(mobile) {
    const mobileRegex = /^[\+]?[1-9][\d]{0,15}$/;
    return mobileRegex.test(mobile.replace(/\s/g, ""));
  }

  // Notification System
  function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
      if (notification.parentNode) {
        notification.remove();
      }
    }, 3000);
  }

  // Confirmation Modal Function
  function showConfirmationModal(userName, parentModal, userID) {
    const confirmationModal = document.createElement("div");
    confirmationModal.className = "confirmation-modal";
    confirmationModal.innerHTML = `
            <div class="confirmation-content">
                <div class="confirmation-header">
                    <h3>⚠️ Remove User</h3>
                </div>
                <div class="confirmation-body">
                    <p>Are you sure you want to remove <strong>${userName}</strong>?</p>
                    <p class="warning-text">This action cannot be undone.</p>
                </div>
                <div class="confirmation-actions">
                    <button class="confirm-remove-btn">Remove User</button>
                    <button class="cancel-remove-btn">Cancel</button>
                </div>
            </div>
        `;

    document.body.appendChild(confirmationModal);

    // Confirmation modal event handlers
    const confirmBtn = confirmationModal.querySelector(".confirm-remove-btn");
    const cancelBtn = confirmationModal.querySelector(".cancel-remove-btn");

    confirmBtn.addEventListener("click", function () {
      // Remove user via AJAX
      fetch(`${URL_ROOT}/admin/deleteUser/${userID}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Remove user from the grid
            removeUserFromGrid(userID);

            // Close both modals
            document.body.removeChild(confirmationModal);
            document.body.removeChild(parentModal);

            // Show success message
            showNotification(
              `${userName} has been removed successfully`,
              "success"
            );
          } else {
            showNotification("Failed to remove user", "error");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showNotification("Error removing user", "error");
        });
    });

    cancelBtn.addEventListener("click", function () {
      document.body.removeChild(confirmationModal);
    });

    // Close confirmation modal when clicking outside
    confirmationModal.addEventListener("click", function (e) {
      if (e.target === confirmationModal) {
        document.body.removeChild(confirmationModal);
      }
    });
  }

  // Function to remove user from the grid
  function removeUserFromGrid(userID) {
    const userCards = document.querySelectorAll(".admin-card");
    userCards.forEach((card) => {
      const cardUserID = card
        .querySelector(".view-btn")
        .getAttribute("data-userid");
      if (cardUserID === userID) {
        card.style.animation = "fadeOut 0.3s ease";
        setTimeout(() => {
          card.remove();
        }, 300);
      }
    });
  }

  // Add modal styles
  function addModalStyles() {
    const modalStyles = `
            <style>
                .modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                }
                .modal-content {
                    background: white;
                    padding: 0;
                    border-radius: 15px;
                    max-width: 600px;
                    width: 90%;
                    position: relative;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                    overflow: hidden;
                    max-height: 90vh;
                    overflow-y: auto;
                }
                .close {
                    position: absolute;
                    top: 15px;
                    right: 20px;
                    font-size: 24px;
                    cursor: pointer;
                    color: #999;
                    z-index: 10;
                }
                .close:hover {
                    color: #333;
                }
                .modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 20px 30px;
                    background: #fafafa;
                    border-bottom: 1px solid #eee;
                }
                .modal-header h2 {
                    font-size: 20px;
                    font-weight: 600;
                    color: #333;
                    margin: 0;
                }
                .edit-toggle-btn {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 16px;
                    background: #ff6b6b;
                    color: white;
                    border: none;
                    border-radius: 20px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                }
                .edit-toggle-btn:hover {
                    background: #ff5252;
                    transform: translateY(-1px);
                }
                .edit-toggle-btn.editing {
                    background: #ff5252;
                }
                .modal-body {
                    padding: 30px;
                }
                .profile-section {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }
                .profile-photo-container {
                    position: relative;
                    margin-bottom: 20px;
                }
                .profile-photo {
                    width: 120px;
                    height: 120px;
                    border-radius: 50%;
                    overflow: hidden;
                    border: 4px solid #ff6b6b;
                }
                .profile-photo img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                .photo-upload-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 107, 107, 0.8);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    color: white;
                    font-size: 12px;
                    font-weight: 500;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }
                .photo-upload-overlay:hover {
                    opacity: 1;
                }
                .user-name {
                    font-size: 24px;
                    font-weight: 700;
                    color: #333;
                    margin-bottom: 20px;
                }
                .contact-details {
                    text-align: left;
                    width: 100%;
                }
                .contact-item {
                    padding: 10px 0;
                    font-size: 14px;
                    color: #333;
                    border-bottom: 1px solid #eee;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                .contact-item:last-child {
                    border-bottom: none;
                }
                .contact-value {
                    flex: 1;
                }
                .contact-input, #statusInput {
                    flex: 1;
                    padding: 6px 10px;
                    border: 2px solid #ff6b6b;
                    border-radius: 6px;
                    font-size: 14px;
                    background: white;
                }
                .contact-input:focus, #statusInput:focus {
                    outline: none;
                    border-color: #ff5252;
                    box-shadow: 0 0 0 2px rgba(255, 82, 82, 0.1);
                }
                .status-active {
                    color: #4CAF50;
                    font-weight: 600;
                }
                .status-inactive {
                    color: #ff9800;
                    font-weight: 600;
                }
                .status-suspended {
                    color: #f44336;
                    font-weight: 600;
                }
                .modal-actions {
                    display: flex;
                    gap: 15px;
                    justify-content: center;
                    padding: 20px 30px;
                    background: #fafafa;
                    border-top: 1px solid #eee;
                }
                .save-btn, .close-btn, .cancel-btn, .remove-btn {
                    padding: 12px 25px;
                    border: none;
                    border-radius: 25px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 14px;
                    transition: all 0.3s ease;
                }
                .save-btn {
                    background: #4CAF50;
                    color: white;
                }
                .save-btn:hover {
                    background: #45a049;
                    transform: translateY(-2px);
                }
                .cancel-btn {
                    background: #ff9800;
                    color: white;
                }
                .cancel-btn:hover {
                    background: #f57c00;
                    transform: translateY(-2px);
                }
                .close-btn {
                    background: #ff6b6b;
                    color: white;
                }
                .close-btn:hover {
                    background: #ff5252;
                    transform: translateY(-2px);
                }
                .remove-btn {
                    background: #f44336;
                    color: white;
                }
                .remove-btn:hover {
                    background: #d32f2f;
                    transform: translateY(-2px);
                }
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 15px 20px;
                    border-radius: 8px;
                    color: white;
                    font-weight: 500;
                    z-index: 1001;
                }
                .notification.success {
                    background: #4CAF50;
                }
                .notification.error {
                    background: #f44336;
                }
                .notification.info {
                    background: #2196F3;
                }
                .confirmation-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 2000;
                }
                .confirmation-content {
                    background: white;
                    padding: 0;
                    border-radius: 15px;
                    max-width: 400px;
                    width: 90%;
                    position: relative;
                    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
                    overflow: hidden;
                }
                .confirmation-header {
                    background: #f44336;
                    color: white;
                    padding: 20px;
                    text-align: center;
                }
                .confirmation-header h3 {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 600;
                }
                .confirmation-body {
                    padding: 30px 20px;
                    text-align: center;
                }
                .confirmation-body p {
                    margin: 0 0 15px 0;
                    font-size: 16px;
                    color: #333;
                    line-height: 1.5;
                }
                .warning-text {
                    color: #f44336;
                    font-weight: 600;
                    font-size: 14px;
                }
                .confirmation-actions {
                    display: flex;
                    gap: 15px;
                    padding: 20px;
                    background: #fafafa;
                    border-top: 1px solid #eee;
                }
                .confirm-remove-btn, .cancel-remove-btn {
                    flex: 1;
                    padding: 12px 20px;
                    border: none;
                    border-radius: 25px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 14px;
                    transition: all 0.3s ease;
                }
                .confirm-remove-btn {
                    background: #f44336;
                    color: white;
                }
                .confirm-remove-btn:hover {
                    background: #d32f2f;
                    transform: translateY(-2px);
                }
                .cancel-remove-btn {
                    background: #9e9e9e;
                    color: white;
                }
                .cancel-remove-btn:hover {
                    background: #757575;
                    transform: translateY(-2px);
                }
                .error-message {
                    color: #f44336;
                    font-size: 12px;
                    margin-top: 5px;
                    display: block;
                }
                @keyframes fadeOut {
                    from {
                        opacity: 1;
                        transform: scale(1);
                    }
                    to {
                        opacity: 0;
                        transform: scale(0.8);
                    }
                }
                @media (max-width: 768px) {
                    .modal-actions {
                        flex-direction: column;
                        gap: 10px;
                    }
                    .modal-header {
                        padding: 15px 20px;
                    }
                    .confirmation-actions {
                        flex-direction: column;
                        gap: 10px;
                    }
                }
            </style>
        `;

    if (!document.querySelector(".modal-styles")) {
      const styleElement = document.createElement("style");
      styleElement.className = "modal-styles";
      styleElement.textContent = modalStyles;
      document.head.appendChild(styleElement);
    }
  }
});
