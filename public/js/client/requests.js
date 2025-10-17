document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    
    // Set minimum date to today for both inputs
    startDateInput.setAttribute('min', today);
    endDateInput.setAttribute('min', today);
    
    // Update end date minimum when start date changes
    startDateInput.addEventListener('change', function() {
        const selectedStartDate = this.value;
        if (selectedStartDate) {
            // End date should not be before start date
            endDateInput.setAttribute('min', selectedStartDate);
            
            // Clear end date if it's before the new start date
            if (endDateInput.value && endDateInput.value < selectedStartDate) {
                endDateInput.value = '';
            }
        } else {
            // If start date is cleared, reset end date minimum to today
            endDateInput.setAttribute('min', today);
        }
    });
    
    // Validate that end date is not before start date
    endDateInput.addEventListener('change', function() {
        const startDate = startDateInput.value;
        const endDate = this.value;
        
        if (startDate && endDate && endDate < startDate) {
            alert('End date cannot be before start date.');
            this.value = '';
        }
    });
});

document.getElementById("requestForm").addEventListener("submit", function (e) {
  e.preventDefault();
  alert("Request Submitted!");
});