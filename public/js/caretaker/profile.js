// Minimal JavaScript for Profile - Image Preview and Modal Only
// Form submissions handled by PHP

document.addEventListener('DOMContentLoaded', function() {
    const profileImageInput = document.getElementById('profileImageInput');
    const previewImg = document.getElementById('previewImg');
    const imagePreview = document.getElementById('imagePreview');
    
    // Image preview on file select
    if (profileImageInput && previewImg && imagePreview) {
        profileImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// Modal functions
function openEditModal(field, value) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('editField').value = field;
    document.getElementById('editValue').value = value;
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function openProfileImageModal() {
    document.getElementById('profileImageModal').style.display = 'flex';
}

function closeProfileImageModal() {
    document.getElementById('profileImageModal').style.display = 'none';
    document.getElementById('imagePreview').style.display = 'none';
}

// Close modal on backdrop click
window.addEventListener('click', function(e) {
    const editModal = document.getElementById('editModal');
    const imageModal = document.getElementById('profileImageModal');
    if (e.target === editModal) closeEditModal();
    if (e.target === imageModal) closeProfileImageModal();
});
