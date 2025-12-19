// Leave Request Form JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const leaveStartDate = document.getElementById('leaveStartDate');
    const leaveEndDate = document.getElementById('leaveEndDate');
    const form = document.querySelector('form');
    const fileInput = document.getElementById('medicalProof');

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    leaveStartDate.setAttribute('min', today);
    leaveEndDate.setAttribute('min', today);

    // Update end date minimum when start date changes
    leaveStartDate.addEventListener('change', function() {
        const startDate = this.value;
        leaveEndDate.setAttribute('min', startDate);
        
        // Clear end date if it's before start date
        if (leaveEndDate.value && leaveEndDate.value < startDate) {
            leaveEndDate.value = '';
        }
    });

    // File upload validation
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        const fileInfo = document.querySelector('.file-info');
        
        if (file) {
            // Check file size (5MB limit)
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
            if (file.size > maxSize) {
                alert('File size must be less than 5MB');
                this.value = '';
                return;
            }
            
            // Check file type
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please upload only PDF, JPG, PNG, DOC, or DOCX files');
                this.value = '';
                return;
            }
            
            // Update file info text
            fileInfo.textContent = `Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            fileInfo.style.color = '#4caf50';
        } else {
            fileInfo.textContent = 'Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max 5MB)';
            fileInfo.style.color = '#666';
        }
    });

    // Form validation before submit
    form.addEventListener('submit', function(e) {
        const startDate = new Date(leaveStartDate.value);
        const endDate = new Date(leaveEndDate.value);
        
        // Validate date range
        if (startDate > endDate) {
            e.preventDefault();
            alert('End date cannot be before start date');
            return false;
        }
        
        // Validate past dates
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (startDate < today) {
            e.preventDefault();
            alert('Start date cannot be in the past');
            return false;
        }
        
        // Calculate leave duration
        const timeDiff = endDate.getTime() - startDate.getTime();
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
        
        // Show confirmation for long leave periods
        if (daysDiff > 30) {
            if (!confirm(`You are requesting ${daysDiff} days of leave. Are you sure you want to proceed?`)) {
                e.preventDefault();
                return false;
            }
        }
        
        return true;
    });

    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 200) + 'px';
        });
    });

    // Add loading state to submit button
    const submitBtn = document.querySelector('.submit-btn');
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        
        // Re-enable after 3 seconds in case of errors
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Leave Request';
        }, 3000);
    });

    // Character counter for textareas (optional enhancement)
    const leaveTypeTextarea = document.getElementById('leaveType');
    const commentsTextarea = document.getElementById('additionalComments');
    
    function addCharacterCounter(textarea, maxLength = 500) {
        const counter = document.createElement('small');
        counter.className = 'char-counter';
        counter.style.cssText = 'display: block; text-align: right; color: #666; margin-top: 5px;';
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${textarea.value.length}/${maxLength} characters`;
            counter.style.color = remaining < 50 ? '#f44336' : '#666';
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter(); // Initialize
    }
    
    addCharacterCounter(leaveTypeTextarea);
    addCharacterCounter(commentsTextarea, 250);
});