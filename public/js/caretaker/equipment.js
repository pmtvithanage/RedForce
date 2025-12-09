// Equipment Requests JavaScript - Caretaker
// Minimal JavaScript for flash messages and modal

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('.alert-success, .alert-danger');
    
    if (flashMessages.length > 0) {
        flashMessages.forEach(function(message) {
            setTimeout(function() {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                
                setTimeout(function() {
                    message.remove();
                }, 500);
            }, 5000);
        });
    }
});

// View details modal function
function viewDetails(requestId) {
    const modal = document.getElementById('detailsModal');
    const modalBody = document.getElementById('modalBody');
    
    if (!modal || !modalBody) return;
    
    // Here you would fetch details via AJAX if needed
    // For now, show a simple message
    modalBody.innerHTML = `
        <h3>Equipment Request Details</h3>
        <p>Request ID: ${requestId}</p>
        <p>Details will be loaded here...</p>
    `;
    
    modal.style.display = 'block';
}

// Close modal function
function closeModal() {
    const modal = document.getElementById('detailsModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('detailsModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
