// Rate Officer Page JavaScript

// Rating labels
const ratingLabels = {
    1: "Poor",
    2: "Fair",
    3: "Good",
    4: "Very Good",
    5: "Excellent"
};

// Update rating label when star is selected
document.querySelectorAll('.star-rating input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const value = this.value;
        const label = document.getElementById('ratingLabel');
        label.textContent = ratingLabels[value];
        label.style.color = '#ffd700';
        label.style.fontWeight = '600';
    });
});

// Form validation
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', function(e) {
        const rating = document.querySelector('.star-rating input[type="radio"]:checked');
        const description = document.getElementById('description');
        
        if (!rating) {
            e.preventDefault();
            alert('Please select a rating');
            return false;
        }
        
        if (description.value.trim().length < 10) {
            e.preventDefault();
            alert('Please provide at least 10 characters in your comments');
            description.focus();
            return false;
        }
        
        return true;
    });
}

// Character counter for textarea
const textarea = document.getElementById('description');
if (textarea) {
    // Create character counter
    const counterDiv = document.createElement('div');
    counterDiv.style.textAlign = 'right';
    counterDiv.style.fontSize = '12px';
    counterDiv.style.color = '#999';
    counterDiv.style.marginTop = '5px';
    textarea.parentNode.appendChild(counterDiv);
    
    // Update counter
    const updateCounter = () => {
        const length = textarea.value.length;
        counterDiv.textContent = `${length} characters`;
        if (length < 10) {
            counterDiv.style.color = '#dc3545';
        } else {
            counterDiv.style.color = '#28a745';
        }
    };
    
    textarea.addEventListener('input', updateCounter);
    updateCounter();
}
