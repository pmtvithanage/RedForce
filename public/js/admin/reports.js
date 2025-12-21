// DOM Elements
const reportCards = document.querySelectorAll('.report-card');
const modals = document.querySelectorAll('.modal');
const closeButtons = document.querySelectorAll('.close');
const generateButtons = document.querySelectorAll('.generate-btn');

// Initialize the dashboard
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
});

function initializeDashboard() {
    // Add click event listeners to report cards
    reportCards.forEach(card => {
        card.addEventListener('click', function() {
            const reportType = this.getAttribute('data-report');
            openModal(reportType);
        });
    });

    // Add click event listeners to close buttons
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            closeAllModals();
        });
    });

    // Close modal when clicking outside
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAllModals();
            }
        });
    });

    // Add event listeners to generate buttons
    generateButtons.forEach(button => {
        button.addEventListener('click', function() {
            generateReport(this);
        });
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });

    // Set default dates for date inputs
    setDefaultDates();
}

function openModal(reportType) {
    const modalId = `${reportType}-modal`;
    const modal = document.getElementById(modalId);
    
    if (modal) {
        // Close any open modals first
        closeAllModals();
        
        // Show the selected modal
        modal.style.display = 'block';
        
        // Add animation class
        modal.classList.add('modal-open');
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Focus on first input in modal
        const firstInput = modal.querySelector('input, select');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

function closeAllModals() {
    modals.forEach(modal => {
        modal.style.display = 'none';
        modal.classList.remove('modal-open');
    });
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

function generateReport(button) {
    const modal = button.closest('.modal');
    const modalId = modal.id;
    
    // Show loading state
    const originalText = button.textContent;
    button.textContent = 'Generating...';
    button.disabled = true;
    
    // Simulate report generation
    setTimeout(() => {
        button.textContent = 'Report Generated!';
        button.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
        
        // Show success message
        showNotification('Report generated successfully!', 'success');
        
        // Reset button after 2 seconds
        setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
            button.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
        }, 2000);
        
        // Update report preview based on modal type
        updateReportPreview(modalId);
        
    }, 1500);
}

function updateReportPreview(modalId) {
    const modal = document.getElementById(modalId);
    const preview = modal.querySelector('.report-preview');
    
    if (!preview) return;
    
    // Add a subtle animation to show the report was updated
    preview.style.animation = 'pulse 0.5s ease';
    setTimeout(() => {
        preview.style.animation = '';
    }, 500);
    
    // Update specific content based on modal type
    switch (modalId) {
        case 'attendance-modal':
            updateAttendancePreview(preview);
            break;
        case 'incident-modal':
            updateIncidentPreview(preview);
            break;
        case 'officer-modal':
            updateOfficerPreview(preview);
            break;
        case 'site-modal':
            updateSitePreview(preview);
            break;
        case 'payment-modal':
            updatePaymentPreview(preview);
            break;
        case 'client-modal':
            updateClientPreview(preview);
            break;
    }
}

function updateAttendancePreview(preview) {
    const stats = preview.querySelectorAll('.stat-number');
    if (stats.length >= 3) {
        // Simulate updated attendance data
        stats[0].textContent = Math.floor(90 + Math.random() * 10) + '%';
        stats[1].textContent = Math.floor(1 + Math.random() * 5) + '%';
        stats[2].textContent = Math.floor(1 + Math.random() * 3) + '%';
    }
}

function updateIncidentPreview(preview) {
    const incidentList = preview.querySelector('.incident-list');
    if (incidentList) {
        // Add a new incident item
        const newIncident = document.createElement('div');
        newIncident.className = 'incident-item';
        newIncident.innerHTML = `
            <span class="incident-type security">Security</span>
            <span class="incident-desc">New incident detected</span>
            <span class="incident-date">${new Date().toISOString().split('T')[0]}</span>
        `;
        incidentList.appendChild(newIncident);
    }
}

function updateOfficerPreview(preview) {
    const metrics = preview.querySelectorAll('.metric-value');
    if (metrics.length >= 3) {
        // Simulate updated performance metrics
        metrics[0].textContent = (1.5 + Math.random() * 2).toFixed(1) + ' min';
        metrics[1].textContent = Math.floor(80 + Math.random() * 20) + '%';
        metrics[2].textContent = (4.0 + Math.random() * 1).toFixed(1) + '/5';
    }
}

function updateSitePreview(preview) {
    const siteStats = preview.querySelectorAll('.stat-value');
    if (siteStats.length >= 3) {
        // Simulate updated site statistics
        siteStats[0].textContent = Math.floor(1000 + Math.random() * 500);
        siteStats[1].textContent = Math.floor(50 + Math.random() * 50);
        siteStats[2].textContent = Math.floor(1 + Math.random() * 5);
    }
}

function updatePaymentPreview(preview) {
    const paymentStats = preview.querySelectorAll('.stat-value');
    if (paymentStats.length >= 3) {
        // Simulate updated payment statistics
        const revenue = Math.floor(40000 + Math.random() * 10000);
        paymentStats[0].textContent = '$' + revenue.toLocaleString();
        paymentStats[1].textContent = Math.floor(100 + Math.random() * 100);
        paymentStats[2].textContent = (95 + Math.random() * 5).toFixed(1) + '%';
    }
}

function updateClientPreview(preview) {
    const transactionList = preview.querySelector('.transaction-list');
    if (transactionList) {
        // Add a new transaction
        const newTransaction = document.createElement('div');
        newTransaction.className = 'transaction-item';
        const amount = Math.floor(1000 + Math.random() * 3000);
        newTransaction.innerHTML = `
            <span class="client-name">New Client</span>
            <span class="transaction-amount">$${amount.toLocaleString()}</span>
            <span class="transaction-date">${new Date().toISOString().split('T')[0]}</span>
        `;
        transactionList.appendChild(newTransaction);
    }
}

function setDefaultDates() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    
    dateInputs.forEach(input => {
        input.value = today;
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-weight: 500;
    `;
    
    // Add to body
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Add CSS for pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    
    .modal-open .modal-content {
        animation: slideIn 0.3s ease;
    }
`;
document.head.appendChild(style);

// Add hover effects for better interactivity
reportCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Add keyboard navigation support
document.addEventListener('keydown', function(e) {
    if (e.key === 'Tab') {
        // Handle tab navigation within modals
        const activeModal = document.querySelector('.modal[style*="block"]');
        if (activeModal) {
            const focusableElements = activeModal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            if (e.shiftKey) {
                // Shift + Tab
                if (document.activeElement === focusableElements[0]) {
                    e.preventDefault();
                    focusableElements[focusableElements.length - 1].focus();
                }
            } else {
                // Tab
                if (document.activeElement === focusableElements[focusableElements.length - 1]) {
                    e.preventDefault();
                    focusableElements[0].focus();
                }
            }
        }
    }
});
