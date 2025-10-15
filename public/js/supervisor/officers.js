// RED FORCE - Feedback System
// JavaScript functionality for the feedback interface

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the feedback system
    const FeedbackSystem = {
        currentRating: 0,
        selectedOfficer: null,
        
        // DOM elements
        elements: {
            starRating: document.getElementById('starRating'),
            description: document.getElementById('description'),
            submitBtn: document.getElementById('submitFeedback'),
            ratingValue: document.querySelector('.rating-value')
        },

        // Initialize the system
        init() {
            this.bindEvents();
            this.loadOfficerData();
            this.setupStarRating();
            this.setupNavigation();
        },

        // Bind event listeners
        bindEvents() {
            // Submit feedback
            this.elements.submitBtn.addEventListener('click', () => this.submitFeedback());
            
            // Auto-resize textarea
            this.elements.description.addEventListener('input', () => this.autoResizeTextarea());
            
            // Form validation
            this.elements.description.addEventListener('input', () => this.validateForm());
        },

        // Setup star rating functionality
        setupStarRating() {
            const stars = this.elements.starRating.querySelectorAll('i');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => this.setRating(index + 1));
                star.addEventListener('mouseenter', () => this.highlightStars(index + 1));
                star.addEventListener('mouseleave', () => this.resetStarHighlight());
            });
        },

        // Set rating
        setRating(rating) {
            this.currentRating = rating;
            this.updateStarDisplay();
            this.validateForm();
        },

        // Highlight stars on hover
        highlightStars(rating) {
            const stars = this.elements.starRating.querySelectorAll('i');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#e91e63';
                } else {
                    star.style.color = '#ddd';
                }
            });
        },

        // Reset star highlight
        resetStarHighlight() {
            this.updateStarDisplay();
        },

        // Update star display
        updateStarDisplay() {
            const stars = this.elements.starRating.querySelectorAll('i');
            stars.forEach((star, index) => {
                if (index < this.currentRating) {
                    star.style.color = '#e91e63';
                    star.classList.add('filled');
                } else {
                    star.style.color = '#ddd';
                    star.classList.remove('filled');
                }
            });
        },

        // Auto-resize textarea
        autoResizeTextarea() {
            const textarea = this.elements.description;
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 200) + 'px';
        },

        // Validate form
        validateForm() {
            const description = this.elements.description.value.trim();
            const isValid = description.length > 0 && this.currentRating > 0;
            
            this.elements.submitBtn.disabled = !isValid;
            this.elements.submitBtn.style.opacity = isValid ? '1' : '0.6';
            this.elements.submitBtn.style.cursor = isValid ? 'pointer' : 'not-allowed';
        },

        // Submit feedback
        submitFeedback() {
            const description = this.elements.description.value.trim();
            
            if (!description || this.currentRating === 0) {
                this.showNotification('Please fill in all required fields', 'error');
                return;
            }

            // Create feedback object
            const feedback = {
                officerId: 'PF231',
                officerName: 'M.W.Viviane Perera',
                rating: this.currentRating,
                description: description,
                timestamp: new Date(),
                submittedBy: 'Supervisor'
            };

            // Simulate API call
            this.showLoadingState();
            
            setTimeout(() => {
                this.hideLoadingState();
                this.showNotification('Feedback submitted successfully!', 'success');
                this.resetForm();
                this.updateOfficerRating();
            }, 1500);
        },

        // Show loading state
        showLoadingState() {
            this.elements.submitBtn.textContent = 'Submitting...';
            this.elements.submitBtn.disabled = true;
        },

        // Hide loading state
        hideLoadingState() {
            this.elements.submitBtn.textContent = 'Submit Feedback';
            this.elements.submitBtn.disabled = false;
        },

        // Reset form
        resetForm() {
            this.elements.description.value = '';
            this.currentRating = 0;
            this.updateStarDisplay();
            this.validateForm();
            this.autoResizeTextarea();
        },

        // Update officer rating (simulate)
        updateOfficerRating() {
            const currentRating = parseInt(this.elements.ratingValue.textContent);
            const newRating = Math.floor((currentRating + this.currentRating) / 2);
            this.elements.ratingValue.textContent = newRating;
            
            // Add animation
            this.elements.ratingValue.style.transform = 'scale(1.2)';
            setTimeout(() => {
                this.elements.ratingValue.style.transform = 'scale(1)';
            }, 200);
        },

        // Show notification
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                font-size: 14px;
                z-index: 1000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                animation: slideIn 0.3s ease-out;
                max-width: 300px;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        },

        // Load officer data (simulate)
        loadOfficerData() {
            // In a real application, this would fetch data from an API
            this.selectedOfficer = {
                id: 'PF231',
                name: 'M.W.Viviane Perera',
                rank: 'OIC',
                location: 'People\'s Bank PLC, No. 112, Sir Chittampalam A. Gardiner Mawatha, Colombo 2',
                rating: 1403
            };
        },

        // Setup navigation
        setupNavigation() {
            document.querySelectorAll('.menu .item').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.handleNavigation(item);
                });
            });
        },

        // Handle navigation
        handleNavigation(item) {
            // Remove active class from all items
            document.querySelectorAll('.menu .item').forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            item.classList.add('active');
            
            const pageName = item.textContent.trim();
            console.log(`Navigating to: ${pageName}`);
            
            // You can add actual navigation logic here
            if (pageName === 'Duty Roster') {
                // Navigate to duty roster page
                window.location.href = 'index6.html';
            }
        },

        // Search officers (for future functionality)
        searchOfficers(query) {
            // This would typically make an API call
            console.log(`Searching for officers: ${query}`);
        },

        // Get officer details (for future functionality)
        getOfficerDetails(officerId) {
            // This would typically make an API call
            console.log(`Getting details for officer: ${officerId}`);
        }
    };

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .rating-value {
            transition: transform 0.2s ease;
        }
        
        .submit-btn {
            transition: all 0.2s ease;
        }
        
        .submit-btn:disabled {
            background: #ccc !important;
            cursor: not-allowed;
        }
    `;
    document.head.appendChild(style);

    // Initialize the feedback system
    FeedbackSystem.init();

    // Add some additional utility functions
    window.FeedbackSystem = FeedbackSystem;

    // Add keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + Enter to submit feedback
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            FeedbackSystem.submitFeedback();
        }
        
        // Escape to reset form
        if (e.key === 'Escape') {
            FeedbackSystem.resetForm();
        }
    });
});

// Export for potential module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FeedbackSystem;
}
