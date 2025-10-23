// ==============================
// Configuration
// ==============================
const URL_ROOT = window.URL_ROOT || '';

// ==============================
// Horizontal drag + wheel scroll for ads rail
// ==============================
(function initHorizontalScroll() {
    const rail = document.getElementById('adsRail');
    if (!rail) return;

    let isDragging = false, startX = 0, scrollStart = 0;

    const startDrag = (e) => {
        isDragging = true;
        rail.classList.add('is-dragging');
        startX = (e.touches ? e.touches[0].pageX : e.pageX) - rail.offsetLeft;
        scrollStart = rail.scrollLeft;
    };

    const endDrag = () => {
        isDragging = false;
        rail.classList.remove('is-dragging');
    };

    const onMove = (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = (e.touches ? e.touches[0].pageX : e.pageX) - rail.offsetLeft;
        rail.scrollLeft = scrollStart - (x - startX);
    };

    // Add event listeners
    const events = [
        ['mousedown', startDrag],
        ['mouseleave', endDrag],
        ['mouseup', endDrag],
        ['mousemove', onMove, { passive: false }],
        ['touchstart', startDrag, { passive: true }],
        ['touchend', endDrag, { passive: true }],
        ['touchmove', onMove, { passive: false }]
    ];

    events.forEach(([event, handler, options]) => {
        rail.addEventListener(event, handler, options);
    });

    // ✅ Keep horizontal scrolling natural, let vertical scroll bubble up
    rail.addEventListener('wheel', (e) => {
        if (Math.abs(e.deltaX) > 0) {
            // only respond to true horizontal scrolls
            rail.scrollLeft += e.deltaX;
            e.preventDefault();
        }
        // if it's deltaY (vertical), do nothing → page scrolls
    }, { passive: false });
})();

// ==============================
// Image upload and form handling
// ==============================
(function initAdvertisementForm() {
    const fileInput = document.getElementById('fileInput');
    const uploadBtn = document.getElementById('uploadBtn');
    const preview = document.getElementById('preview');
    const form = document.getElementById('createForm');
    const hint = document.getElementById('formHint');
    const rail = document.getElementById('adsRail');
    const uploader = document.getElementById('uploader');
    const backdrop = document.getElementById('backdrop');
    const editModal = document.getElementById('editForm');
    const editFormInner = document.getElementById('editFormInner');
    const editFileInput = document.getElementById('editFileInput');
    const editPreview = document.getElementById('editPreview');
    const editHint = document.getElementById('editHint');

    if (!fileInput || !form || !preview) return;

    // Helper functions
const safeJsonParse = async (response) => {
    const text = await response.text();
    
    // Check if response is a server error
    if (!response.ok) {
        console.error('Server error:', response.status, response.statusText);
        console.error('Error response:', text);
        
        // Try to extract JSON from error response
        try {
            const errorData = JSON.parse(text);
            return {
                status: 'error',
                message: errorData.message || `Server error (${response.status}): ${response.statusText}`
            };
        } catch (e) {
            // If not JSON, return generic error with status code
            return {
                status: 'error',
                message: `Server error (${response.status}): ${response.statusText}. Please check console for details.`
            };
        }
    }
    
    try {
        return JSON.parse(text);
    } catch (e) {
        console.error('Failed to parse JSON response:', text);
        return {
            status: 'error',
            message: 'Server returned invalid response. Please check console for details.'
        };
    }
};

const showHint = (message, type = 'error') => {
    hint.textContent = message;
    hint.style.color = type === 'error' ? '#dc2626' : 
                      type === 'success' ? '#059669' :
                      type === 'info' ? '#d97706' : '#6b7280';
};

const showEditHint = (message, type = 'error') => {
    editHint.textContent = message;
    editHint.style.color = type === 'error' ? '#dc2626' : 
                          type === 'success' ? '#059669' :
                          type === 'info' ? '#d97706' : '#6b7280';
};

const resetForm = () => {
    form.reset();
    preview.innerHTML = '<span>Upload Advertisement</span>';
    // Also reset the file input
    fileInput.value = '';
};

// Event handlers
const handleFileSelect = () => {
    uploadBtn?.addEventListener('click', () => fileInput.click());
    uploader?.addEventListener('click', (e) => {
        if (e.target === uploader || e.target === preview) fileInput.click();
    });
};

const handleImagePreview = () => {
    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (!file) return;
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            showHint('⚠ Please select a valid image (JPEG, PNG, GIF, WebP).');
            fileInput.value = '';
            return;
        }
        
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            showHint('⚠ Image must be less than 5MB.');
            fileInput.value = '';
            return;
        }
        
        preview.innerHTML = '';
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.alt = 'Selected advertisement';
        img.onload = () => URL.revokeObjectURL(img.src); // Clean up memory
        preview.appendChild(img);
    });
};

const handleFormSubmit = async (e) => {
    e.preventDefault();

    const roles = Array.from(form.querySelectorAll('input[name="roles[]"]:checked')).map(el => el.value);
    const title = form.querySelector('input[name="title"]').value.trim();

    // Clear previous errors
    showHint('');

    // Validation
    if (!fileInput.files.length) {
        showHint('⚠ Please upload an image.');
        return;
    }
    if (!roles.length) {
        showHint('⚠ Select at least one role.');
        return;
    }
    if (!title) {
        showHint('⚠ Please enter a title.');
        return;
    }
    if (title.length > 100) {
        showHint('⚠ Title must be less than 100 characters.');
        return;
    }

    const formData = new FormData();
    formData.append('image', fileInput.files[0]);
    formData.append('roles', roles.join(','));
    formData.append('title', title);

    try {
        showHint('⏳ Creating advertisement...', 'info');

        const res = await fetch(`${URL_ROOT}/admin/createAdvertisement`, { 
            method: 'POST', 
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Identify as AJAX request
            }
        });
        
        const result = await safeJsonParse(res);
        console.log('Server response:', result);

        if (result.status === 'success') {
            showHint('✅ Advertisement published successfully!', 'success');
            resetForm();
            
            // Add new card to the list
            if (result.ad) {
                addAdvertisementCard(result.ad);
            }
            
            // Optional: Scroll to the new card
            setTimeout(() => {
                const newCard = document.querySelector(`.ad-card[data-id="${result.ad.id}"]`);
                if (newCard) {
                    newCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 100);
            
        } else {
            // Show specific error message from server
            showHint('⚠ ' + (result.message || 'Failed to create advertisement. Please try again.'));
            
            // If it's a server error, offer to reload
            if (result.message?.includes('Server error')) {
                setTimeout(() => {
                    if (confirm('Server error occurred. Would you like to reload the page?')) {
                        location.reload();
                    }
                }, 2000);
            }
        }
    } catch (err) {
        console.error('Network error:', err);
        showHint('⚠ Network error. Please check your connection and try again.');
    }
};

// Add this function to handle server errors better
const checkServerHealth = async () => {
    try {
        const res = await fetch(`${URL_ROOT}/admin/testEndpoint`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!res.ok) {
            console.warn('Server health check failed:', res.status);
        }
    } catch (err) {
        console.warn('Server health check failed:', err);
    }
};

// Optional: Add a server health check on page load
document.addEventListener('DOMContentLoaded', () => {
    checkServerHealth();
});

    const addAdvertisementCard = (adData) => {
        if (!rail) return;

        const card = document.createElement('article');
        card.className = 'ad-card';
        card.dataset.id = adData.id;
        card.innerHTML = `
            <button class="ad-card__close" aria-label="Remove">×</button>
            <button class="ad-card__edit" aria-label="Edit">✎</button>
            <img src="${adData.image_path}" alt="Advertisement Image" class="ad-card__image" />
            <footer class="ad-card__meta">
                <div class="ad-card__role"><span>${adData.target_roles}</span></div>
                <div class="ad-card__date">
                    <div class="muted">Published</div>
                    <div>${new Date(adData.created_at).toLocaleDateString()}</div>
                </div>
                <div class="ad-card__status">
                    <span>Status: ${adData.status}</span>
                    <button type="button" class="btn btn--small" data-toggle-status>Toggle</button>
                </div>
            </footer>
        `;
        
        rail.prepend(card);
        attachCardHandlers(card);
    };

    const openEditModal = async (adId) => {
        try {
            const res = await fetch(`${URL_ROOT}/admin/getAdvertisement/${adId}`);
            const result = await safeJsonParse(res);
            
            if (result.status === 'success') {
                populateEditForm(result.advertisement);
                backdrop.hidden = false;
                editModal.hidden = false;
            } else {
                alert('Failed to load advertisement details: ' + result.message);
            }
        } catch (err) {
            console.error(err);
            alert('Failed to load advertisement details.');
        }
    };

    const populateEditForm = (ad) => {
    try {
        document.getElementById('editId').value = ad.id;
        document.getElementById('editTitle').value = ad.title;
        document.getElementById('currentImagePath').value = ad.image_path;
        
        // Set current image with error handling
        editPreview.innerHTML = '';
        const img = document.createElement('img');
        
        // Create full image URL
        let imageUrl = ad.image_path;
        
        // If it's not already a full URL, prepend the base URL
        if (!imageUrl.startsWith('http') && !imageUrl.startsWith('//')) {
            if (!imageUrl.startsWith('/')) {
                imageUrl = '/' + imageUrl;
            }
            imageUrl = URL_ROOT + imageUrl;
        }
        
        img.src = imageUrl;
        img.alt = 'Current advertisement';
        img.style.maxWidth = '100%';
        img.style.maxHeight = '100%';
        img.style.objectFit = 'cover';
        
        // Add error handling for broken images
        img.onerror = function() {
            console.error('Failed to load image:', imageUrl);
            this.style.display = 'none';
            editPreview.innerHTML = `
                <div style="text-align: center; color: #6b7280; padding: 20px;">
                    <div>⚠️ Image not found</div>
                    <div style="font-size: 12px; margin-top: 8px;">${ad.image_path}</div>
                </div>
            `;
        };
        
        img.onload = function() {
            console.log('Image loaded successfully:', imageUrl);
        };
        
        editPreview.appendChild(img);
        
        // Set current roles with trimming and case sensitivity handling
        const roles = ad.target_roles.split(',').map(role => role.trim());
        document.querySelectorAll('#editFormInner input[name="editRoles[]"]').forEach(checkbox => {
            // Case insensitive comparison
            const checkboxValue = checkbox.value.trim().toLowerCase();
            const hasRole = roles.some(role => role.toLowerCase() === checkboxValue);
            checkbox.checked = hasRole;
        });
        
        // Set current status
        const statusInput = document.querySelector(`#editFormInner input[name="status"][value="${ad.status}"]`);
        if (statusInput) {
            statusInput.checked = true;
        } else {
            console.warn('Status input not found for value:', ad.status);
            // Set default to active if not found
            const defaultStatus = document.querySelector(`#editFormInner input[name="status"][value="active"]`);
            if (defaultStatus) defaultStatus.checked = true;
        }
        
    } catch (error) {
        console.error('Error populating edit form:', error);
        editPreview.innerHTML = `
            <div style="text-align: center; color: #dc2626; padding: 20px;">
                <div>⚠️ Error loading form data</div>
                <div style="font-size: 12px; margin-top: 8px;">${error.message}</div>
            </div>
        `;
    }
};

    const handleEditFormSubmit = async (e) => {
        e.preventDefault();
        
        const adId = document.getElementById('editId').value;
        const roles = Array.from(editFormInner.querySelectorAll('input[name="editRoles[]"]:checked')).map(el => el.value);
        const statusInput = editFormInner.querySelector('input[name="status"]:checked');
        
        if (!statusInput) {
            showEditHint('⚠ Please select a status.');
            return;
        }
        
        const status = statusInput.value;
        const formData = new FormData();
        formData.append('title', document.getElementById('editTitle').value);
        formData.append('roles', roles.join(','));
        formData.append('status', status);
        formData.append('current_image', document.getElementById('currentImagePath').value);
        
        if (editFileInput.files.length) {
            formData.append('image', editFileInput.files[0]);
        }
        
        try {
            showEditHint('⏳ Updating advertisement...', 'info');
            
            const res = await fetch(`${URL_ROOT}/admin/updateAdvertisement/${adId}`, {
                method: 'POST',
                body: formData
            });
            
            const result = await safeJsonParse(res);
            
            if (result.status === 'success') {
                showEditHint('✅ Advertisement updated', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showEditHint('⚠ ' + result.message);
            }
        } catch (err) {
            console.error(err);
            showEditHint('⚠ Failed to update advertisement.');
        }
    };

    const handleEditImagePreview = () => {
        editFileInput.addEventListener('change', () => {
            const file = editFileInput.files[0];
            if (!file) return;
            
            editPreview.innerHTML = '';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = 'New advertisement image';
            editPreview.appendChild(img);
        });
    };

    const closeModal = () => {
        backdrop.hidden = true;
        editModal.hidden = true;
        editHint.textContent = '';
        editFileInput.value = '';
    };

    const attachCardHandlers = (card) => {
        const adId = card.dataset.id;
        
        // Delete handler
        card.querySelector('.ad-card__close')?.addEventListener('click', async () => {
            if (!confirm('Delete this advertisement?')) return;

            try {
                const res = await fetch(`${URL_ROOT}/admin/deleteAdvertisement/${adId}`, { method: 'POST' });
                const data = await safeJsonParse(res);
                if (data.status === 'success') card.remove();
                else alert(data.message);
            } catch (err) {
                console.error(err);
                alert('Failed to delete advertisement.');
            }
        });

        // Edit handler
        card.querySelector('.ad-card__edit')?.addEventListener('click', () => {
            openEditModal(adId);
        });

        // Toggle status handler
        card.querySelector('[data-toggle-status]')?.addEventListener('click', async () => {
            try {
                const res = await fetch(`${URL_ROOT}/admin/toggleAdvertisementStatus/${adId}`, { method: 'POST' });
                const data = await safeJsonParse(res);
                if (data.status === 'success') {
                    const statusEl = card.querySelector('.ad-card__status span');
                    if (statusEl) {
                        const isActive = statusEl.textContent.includes('Active');
                        statusEl.textContent = isActive ? 'Status: Inactive' : 'Status: Active';
                    }
                } else {
                    alert(data.message);
                }
            } catch (err) {
                console.error(err);
                alert('Failed to toggle status.');
            }
        });
    };

    // Initialize event listeners
    handleFileSelect();
    handleImagePreview();
    handleEditImagePreview();
    
    form.addEventListener('submit', handleFormSubmit);
    editFormInner.addEventListener('submit', handleEditFormSubmit);
    backdrop.addEventListener('click', closeModal);

    // Attach handlers to existing cards
    document.querySelectorAll('.ad-card').forEach(attachCardHandlers);
})();