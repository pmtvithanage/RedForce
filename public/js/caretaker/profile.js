// Toast helper
function showToast(message){
  const toast=document.getElementById('toast');
  if(!toast) return;
  toast.textContent=message;
  toast.classList.add('show');
  setTimeout(()=>toast.classList.remove('show'),2500);
}

// Profile Image Management
let selectedImageFile = null;

// Profile Image functionality
function initializeProfileImage() {
    console.log('Initializing profile image functionality...');
    
    const editAvatar = document.getElementById('editAvatar');
    const profileOverlay = document.getElementById('profileOverlay');
    const profileImageModal = document.getElementById('profileImageModal');
    const profileImageInput = document.getElementById('profileImageInput');
    const profileImage = document.getElementById('profileImage');
    const profileIcon = document.getElementById('profileIcon');
    const uploadImageBtn = document.getElementById('uploadImageBtn');
    const useDefaultBtn = document.getElementById('useDefaultBtn');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const saveImageBtn = document.getElementById('saveImageBtn');
    const cancelImageBtn = document.getElementById('cancelImageBtn');

    // Function to close modal
    function closeModal() {
        console.log('Closing modal...');
        if (profileImageModal) {
            profileImageModal.style.display = 'none';
            profileImageModal.setAttribute('hidden', '');
        }
        if (imagePreview) {
            imagePreview.style.display = 'none';
        }
        if (profileImageInput) {
            profileImageInput.value = '';
        }
        selectedImageFile = null;
        console.log('Modal closed successfully');
    }

    // Function to open modal
    function openProfileModal() {
        console.log('Opening profile modal...');
        if (profileImageModal) {
            profileImageModal.style.display = 'flex';
            profileImageModal.removeAttribute('hidden');
        }
    }

    // Open modal when "Change Profile Image" button is clicked
    const changeProfileImageBtn = document.getElementById('changeProfileImageBtn');
    if (changeProfileImageBtn) {
        changeProfileImageBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            openProfileModal();
        });
    }

    // Close modal button event
    if (closeModalBtn) {
        closeModalBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeModal();
            return false;
        };
        
        closeModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeModal();
        });
    }

    // Close modal when clicking outside
    if (profileImageModal) {
        profileImageModal.addEventListener('click', (e) => {
            if (e.target === profileImageModal) {
                closeModal();
            }
        });
    }

    // Escape key to close modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && profileImageModal && !profileImageModal.hasAttribute('hidden')) {
            closeModal();
        }
    });

    // Upload image button
    if (uploadImageBtn) {
        uploadImageBtn.addEventListener('click', () => {
            profileImageInput.click();
        });
    }

    // Handle file selection
    if (profileImageInput) {
        profileImageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (5MB limit)
                if (file.size > 5 * 1024 * 1024) {
                    showToast('File size must be less than 5MB');
                    return;
                }
                
                if (file.type.startsWith('image/')) {
                    selectedImageFile = file;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImage.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    showToast('Please select a valid image file');
                }
            }
        });
    }

    // Save image
    if (saveImageBtn) {
        saveImageBtn.addEventListener('click', () => {
            if (selectedImageFile) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profileImage.src = e.target.result;
                    profileImage.style.display = 'block';
                    profileIcon.style.display = 'none';
                    
                    // Save to localStorage for persistence
                    localStorage.setItem('caretakerProfileImage', e.target.result);
                    
                    showToast('Profile image updated successfully!');
                    closeModal();
                };
                reader.readAsDataURL(selectedImageFile);
            }
        });
    }

    // Cancel image
    if (cancelImageBtn) {
        cancelImageBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (imagePreview) {
                imagePreview.style.display = 'none';
            }
            selectedImageFile = null;
            if (profileImageInput) {
                profileImageInput.value = '';
            }
        });
    }

    // Use default icon
    if (useDefaultBtn) {
        useDefaultBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (profileImage) {
                profileImage.style.display = 'none';
            }
            if (profileIcon) {
                profileIcon.style.display = 'block';
            }
            localStorage.removeItem('caretakerProfileImage');
            showToast('Using default profile icon');
            closeModal();
        });
    }

    // Remove image
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (profileImage) {
                profileImage.style.display = 'none';
            }
            if (profileIcon) {
                profileIcon.style.display = 'block';
            }
            localStorage.removeItem('caretakerProfileImage');
            showToast('Profile image removed');
            closeModal();
        });
    }

    // Load saved profile image on page load
    const savedImage = localStorage.getItem('caretakerProfileImage');
    if (savedImage) {
        profileImage.src = savedImage;
        profileImage.style.display = 'block';
        profileIcon.style.display = 'none';
    }
}

// Wire actions after DOM ready
document.addEventListener('DOMContentLoaded',()=>{
  // Initialize profile image functionality
  initializeProfileImage();

  // Logout
  const logoutBtn=document.getElementById('logoutBtn');
  if(logoutBtn){
    logoutBtn.addEventListener('click',()=>{
      showToast('You have been logged out.');
    });
  }

     // Inline edits
   document.querySelectorAll('.icon-btn,[data-edit]').forEach(btn=>{
     btn.addEventListener('click',()=>{
       const key=btn.getAttribute('data-edit');
       if(!key) return;
       const map={
         name:{label:'Mobile Rider Name', el:'#nameValue'},
         password:{label:'Password', el:'#passwordValue', type:'password'},
         contact:{label:'Contact Number', el:'#contactValue'},
         email:{label:'Email', el:'#emailValue'}
       };
       const entry=map[key];
       if(!entry) return;
       const valueEl=document.querySelector(entry.el);
       
       if(entry.type === 'password') {
         // Handle password change with confirmation
         const currentPassword = prompt('Enter current password:');
         if(currentPassword === null) return;
         
         // In a real app, you'd verify against stored password
         if(currentPassword !== 'current123') { // Demo password
           showToast('Current password is incorrect');
           return;
         }
         
         const newPassword = prompt('Enter new password (min 8 characters):');
         if(newPassword === null) return;
         
         if(newPassword.length < 8) {
           showToast('Password must be at least 8 characters long');
           return;
         }
         
         const confirmPassword = prompt('Confirm new password:');
         if(confirmPassword === null) return;
         
         if(newPassword !== confirmPassword) {
           showToast('Passwords do not match');
           return;
         }
         
         if(valueEl) { 
           valueEl.textContent = '- ••••••••'; 
         }
         showToast('Password updated successfully');
       } else {
         // Handle regular field updates
         const current = valueEl ? valueEl.textContent.replace(/^\s*-\s*/,'').trim() : '';
         const next = prompt(`Update ${entry.label}:`, current);
         if(next !== null){
           if(valueEl){ valueEl.textContent = `- ${next.trim()}`; }
           showToast(`${entry.label} updated`);
         }
       }
     });
   });
});
