
// ==============================
// TOAST HELPER
// ==============================
function showToast(message) {
  const toast = document.getElementById("toast");
  if (!toast) return;
  toast.textContent = message;
  toast.classList.add("show");
  setTimeout(() => toast.classList.remove("show"), 2500);
}

// ==============================
// GLOBAL VARIABLES
// ==============================
let currentField = '';
let currentValue = '';
let selectedImageFile = null;

// ==============================
// DOM ELEMENTS
// ==============================
const editModal = document.getElementById('editModal');
const profileImageModal = document.getElementById('profileImageModal');
const editForm = document.getElementById('editForm');
const profileImageForm = document.getElementById('profileImageForm');
const modalTitle = document.getElementById('modalTitle');
const fieldLabel = document.getElementById('fieldLabel');
const fieldInput = document.getElementById('fieldInput');
const passwordInput = document.getElementById('passwordInput');
const confirmPasswordInput = document.getElementById('confirmPasswordInput');
const profileImageInput = document.getElementById('profileImageInput');
const imagePreview = document.getElementById('imagePreview');
const previewImg = document.getElementById('previewImg');

// ==============================
// MODAL FUNCTIONS
// ==============================
function openEditModal(field, value) {
  currentField = field;
  currentValue = value;

  editForm.reset();
  fieldInput.style.display = 'block';
  passwordInput.style.display = 'none';
  confirmPasswordInput.style.display = 'none';

  switch (field) {
    case 'name':
      modalTitle.textContent = 'Change Name';
      fieldLabel.textContent = 'Full Name';
      fieldInput.value = value;
      fieldInput.type = 'text';
      fieldInput.placeholder = 'Enter your full name';
      break;
    case 'password':
      modalTitle.textContent = 'Change Password';
      fieldLabel.textContent = 'New Password';
      fieldInput.style.display = 'none';
      passwordInput.style.display = 'block';
      confirmPasswordInput.style.display = 'block';
      break;
    case 'contact':
      modalTitle.textContent = 'Change Contact Number';
      fieldLabel.textContent = 'Contact Number';
      fieldInput.value = value;
      fieldInput.type = 'tel';
      break;
    case 'email':
      modalTitle.textContent = 'Change Email';
      fieldLabel.textContent = 'Email Address';
      fieldInput.value = value;
      fieldInput.type = 'email';
      break;
  }

  editModal.style.display = 'flex';
}

function closeEditModal() {
  editModal.style.display = 'none';
  editForm.reset();
}

function openProfileImageModal() {
  profileImageModal.style.display = 'flex';
  imagePreview.style.display = 'none';
}

function closeProfileImageModal() {
  profileImageModal.style.display = 'none';
  profileImageForm.reset();
  imagePreview.style.display = 'none';
}

// ==============================
// FORM HANDLERS
// ==============================
editForm.addEventListener('submit', function (e) {
  e.preventDefault();
  let newValue = '';
  let isValid = true;
  let errorMessage = '';

  switch (currentField) {
    case 'name':
      newValue = fieldInput.value.trim();
      if (newValue.length < 2) {
        isValid = false;
        errorMessage = 'Name must be at least 2 characters long';
      }
      break;
    case 'password':
      const password = passwordInput.value;
      const confirmPassword = confirmPasswordInput.value;
      if (password.length < 6) {
        isValid = false;
        errorMessage = 'Password must be at least 6 characters long';
      } else if (password !== confirmPassword) {
        isValid = false;
        errorMessage = 'Passwords do not match';
      } else {
        newValue = password;
      }
      break;
    case 'contact':
      newValue = fieldInput.value.trim();
      const phoneRegex = /^[0-9+\-\s()]+$/;
      if (!phoneRegex.test(newValue) || newValue.length < 10) {
        isValid = false;
        errorMessage = 'Please enter a valid contact number';
      }
      break;
    case 'email':
      newValue = fieldInput.value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(newValue)) {
        isValid = false;
        errorMessage = 'Please enter a valid email address';
      }
      break;
  }

  if (!isValid) {
    showNotification(errorMessage, 'error');
    return;
  }

  updateProfileField(currentField, newValue);
});

profileImageForm.addEventListener('submit', function (e) {
  e.preventDefault();
  const file = profileImageInput.files[0];
  if (!file) {
    showNotification('Please select an image', 'error');
    return;
  }
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
  if (!allowedTypes.includes(file.type)) {
    showNotification('Invalid image file type', 'error');
    return;
  }
  if (file.size > 5 * 1024 * 1024) {
    showNotification('Image size must be less than 5MB', 'error');
    return;
  }
  uploadProfileImage(file);
});

// ==============================
// PROFILE UPDATE FUNCTIONS
// ==============================
function updateProfileField(field, value) {
  showNotification('Updating...', 'info');
  setTimeout(() => {
    const infoRows = document.querySelectorAll('.info-row');
    infoRows.forEach(row => {
      const label = row.querySelector('.info-label').textContent.toLowerCase();
      if ((field === 'name' && label.includes('name')) ||
        (field === 'password' && label.includes('password')) ||
        (field === 'contact' && label.includes('contact')) ||
        (field === 'email' && label.includes('email'))) {
        const valueElement = row.querySelector('.info-value');
        valueElement.textContent = field === 'password' ? '- ••••••••' : '- ' + value;
      }
    });
    closeEditModal();
    showNotification('Profile updated successfully!', 'success');
  }, 1000);
}

function uploadProfileImage(file) {
  showNotification('Uploading image...', 'info');
  setTimeout(() => {
    const reader = new FileReader();
    reader.onload = function (e) {
      const profilePicture = document.querySelector('.profile-picture');
      profilePicture.innerHTML = `<img src="${e.target.result}" alt="Profile" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
    };
    reader.readAsDataURL(file);
    closeProfileImageModal();
    showNotification('Profile image updated successfully!', 'success');
  }, 1500);
}

// ==============================
// NOTIFICATION SYSTEM
// ==============================
function showNotification(message, type = 'info') {
  const existing = document.querySelectorAll('.notification');
  existing.forEach(n => n.remove());

  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
    <div class="notification-content">
      <span class="notification-message">${message}</span>
      <button class="notification-close">&times;</button>
    </div>
  `;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: ${getNotificationColor(type)};
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 10000;
    max-width: 400px;
    animation: slideIn 0.3s ease-out;
  `;
  document.body.appendChild(notification);
  notification.querySelector('.notification-close').addEventListener('click', () => notification.remove());
  setTimeout(() => notification.remove(), 5000);
}

function getNotificationColor(type) {
  switch (type) {
    case 'success': return '#28a745';
    case 'error': return '#dc3545';
    case 'warning': return '#ffc107';
    default: return '#17a2b8';
  }
}

const style = document.createElement('style');
style.textContent = `
@keyframes slideIn { from {transform:translateX(100%);opacity:0;} to {transform:translateX(0);opacity:1;} }
.notification-content {display:flex;justify-content:space-between;align-items:center;}
.notification-close {background:none;border:none;color:white;font-size:18px;cursor:pointer;margin-left:10px;}
`;
document.head.appendChild(style);

// ==============================
// PROFILE IMAGE INITIALIZATION
// ==============================
function initializeProfileImage() {
  const profileImage = document.getElementById("profileImage");
  const profileIcon = document.getElementById("profileIcon");
  const useDefaultBtn = document.getElementById("useDefaultBtn");
  const removeImageBtn = document.getElementById("removeImageBtn");
  const saveImageBtn = document.getElementById("saveImageBtn");
  const cancelImageBtn = document.getElementById("cancelImageBtn");

  if (profileImageInput) {
    profileImageInput.addEventListener("change", (e) => {
      const file = e.target.files[0];
      if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = (e) => {
          previewImg.src = e.target.result;
          imagePreview.style.display = "block";
          selectedImageFile = file;
        };
        reader.readAsDataURL(file);
      } else {
        showToast("Please select a valid image file");
      }
    });
  }

  if (saveImageBtn) {
    saveImageBtn.addEventListener("click", () => {
      if (selectedImageFile) {
        const reader = new FileReader();
        reader.onload = (e) => {
          profileImage.src = e.target.result;
          profileImage.style.display = "block";
          profileIcon.style.display = "none";
          localStorage.setItem("caretakerProfileImage", e.target.result);
          showToast("Profile image updated successfully!");
          closeProfileImageModal();
        };
        reader.readAsDataURL(selectedImageFile);
      }
    });
  }

  if (useDefaultBtn) {
    useDefaultBtn.addEventListener("click", () => {
      profileImage.style.display = "none";
      profileIcon.style.display = "block";
      localStorage.removeItem("caretakerProfileImage");
      showToast("Using default profile icon");
      closeProfileImageModal();
    });
  }

  if (removeImageBtn) {
    removeImageBtn.addEventListener("click", () => {
      profileImage.style.display = "none";
      profileIcon.style.display = "block";
      localStorage.removeItem("caretakerProfileImage");
      showToast("Profile image removed");
      closeProfileImageModal();
    });
  }

  if (cancelImageBtn) {
    cancelImageBtn.addEventListener("click", () => {
      imagePreview.style.display = "none";
      selectedImageFile = null;
      profileImageInput.value = "";
    });
  }

  const savedImage = localStorage.getItem("caretakerProfileImage");
  if (savedImage) {
    profileImage.src = savedImage;
    profileImage.style.display = "block";
    profileIcon.style.display = "none";
  }
}

// ==============================
// INITIALIZATION
// ==============================
document.addEventListener('DOMContentLoaded', function () {
  console.log('Profile page loaded successfully!');
  initializeProfileImage();
});

window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
window.openProfileImageModal = openProfileImageModal;
window.closeProfileImageModal = closeProfileImageModal;
