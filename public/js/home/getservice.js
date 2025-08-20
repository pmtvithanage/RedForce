let rowCounter = 5;

// File upload functionality
function setupFileUpload() {
    const uploadBtn = document.querySelector('.upload-btn');
    const logoPlaceholder = document.querySelector('.logo-placeholder');
    
    // Create a hidden file input
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.style.display = 'none';
    fileInput.id = 'logoFileInput';
    
    // Add file input to the page
    document.body.appendChild(fileInput);
    
    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file (JPEG, PNG, GIF, etc.)');
                return;
            }
            
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                return;
            }
            
            // Display the image
            displayUploadedImage(file, logoPlaceholder);
        }
    });
    
    // Handle upload button click
    uploadBtn.addEventListener('click', function() {
        fileInput.click();
    });
}

function displayUploadedImage(file, container) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        // Clear the container
        container.innerHTML = '';
        
        // Create and display the image
        const img = document.createElement('img');
        img.src = e.target.result;
        img.alt = 'Uploaded Logo';
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.objectFit = 'cover';
        img.style.borderRadius = '50%';
        
        // Add remove button
        const removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕';
        removeBtn.className = 'remove-logo-btn';
        removeBtn.style.cssText = `
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        `;
        
        removeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            resetLogoPlaceholder(container);
        });
        
        // Make container relative for absolute positioning
        container.style.position = 'relative';
        container.appendChild(img);
        container.appendChild(removeBtn);
        
        // Store the file data for form submission
        container.dataset.uploadedFile = JSON.stringify({
            name: file.name,
            size: file.size,
            type: file.type
        });
    };
    
    reader.readAsDataURL(file);
}

function resetLogoPlaceholder(container) {
    container.innerHTML = '<div class="upload-icon">📷</div>';
    container.style.position = 'static';
    delete container.dataset.uploadedFile;
}

function addNewRow() {
    const tbody = document.querySelector('#serviceTable tbody');
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td>${rowCounter}</td>
        <td><input type="text" class="site-address" placeholder="Enter site address"></td>
        <td><input type="number" class="security-officers" value="0" min="0" max="100" placeholder="Select"></td>
        <td><input type="number" class="care-takers" value="0" min="0" max="100" placeholder="Select"></td>
        <td>
            <select class="shift-type">
                <option value="Day">Day</option>
                <option value="Night">Night</option>
                <option value="Both">Both</option>
            </select>
        </td>
        <td><button class="remove-btn" onclick="removeRow(this)">Remove</button></td>
    `;
    
    tbody.appendChild(newRow);
    rowCounter++;
    updateRowNumbers();
}

function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
    updateRowNumbers();
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#serviceTable tbody tr');
    rows.forEach((row, index) => {
        row.cells[0].textContent = index + 1;
    });
    rowCounter = rows.length + 1;
}

function cancel() {
    window.history.back();
}


function submitForm() {
    // Collect all form data
    const formData = {
        companyName: document.getElementById('company-name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        ownerName: document.getElementById('owner-name').value,
        logoFile: null,
        sites: []
    };

    // Get logo file data if uploaded
    const logoPlaceholder = document.querySelector('.logo-placeholder');
    if (logoPlaceholder.dataset.uploadedFile) {
        formData.logoFile = JSON.parse(logoPlaceholder.dataset.uploadedFile);
    }

    // Collect table data
    const rows = document.querySelectorAll('#serviceTable tbody tr');
    rows.forEach(row => {
        const siteData = {
            address: row.querySelector('.site-address').value,
            securityOfficers: parseInt(row.querySelector('.security-officers').value) || 0,
            careTakers: parseInt(row.querySelector('.care-takers').value) || 0,
            shiftType: row.querySelector('.shift-type').value
        };
        formData.sites.push(siteData);
    });

    // Display the collected data (you can modify this to send to server)
    console.log('Form Data:', formData);
    alert('Form submitted successfully! Check console for data.');
}

// Initialize the form when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Setup file upload functionality
    setupFileUpload();
    
    // Any other initialization code can go here
    console.log('Red Force Security Service form loaded successfully!');
});
