// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modals = document.getElementsByClassName('modal');
    for (let modal of modals) {
        if (event.target == modal) {
            closeModal(modal.id);
        }
    }
}

// View advertisement details in modal
function viewAdvertisement(adId) {
    const fullAdContent = document.getElementById(`full-ad-${adId}`);
    const modalBody = document.getElementById('adModalBody');
    
    if (fullAdContent && modalBody) {
        modalBody.innerHTML = fullAdContent.innerHTML;
        openModal('adModal');
    } else {
        console.error('Advertisement content not found for ID:', adId);
    }
}

// Refresh advertisements
function refreshAdvertisements() {
    window.location.reload();
}

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Premise Officer Dashboard loaded successfully');
});
