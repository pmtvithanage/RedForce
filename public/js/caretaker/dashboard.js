// Minimal JavaScript for Caretaker Dashboard
// Most functionality handled by PHP

document.addEventListener('DOMContentLoaded', function() {
    console.log('Caretaker Dashboard loaded');
    
    // File upload functionality
    const uploadBtn = document.getElementById('uploadBtn');
    const fileInput = document.getElementById('photoUpload');
    const fileList = document.getElementById('fileList');
    const submitBtn = document.getElementById('submitBtn');
    
    // Upload button triggers file input
    if (uploadBtn && fileInput) {
        uploadBtn.addEventListener('click', function() {
            fileInput.click();
        });
    }
    
    // File input change - show previews
    if (fileInput && fileList && submitBtn) {
        fileInput.addEventListener('change', function(e) {
            fileList.innerHTML = '';
            const files = Array.from(e.target.files);
            
            if (files.length > 0) {
                files.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-preview';
                    fileItem.innerHTML = `
                        <span class="file-name">${file.name}</span>
                        <button type="button" class="remove-file" onclick="removeFile(${index})">×</button>
                    `;
                    fileList.appendChild(fileItem);
                });
                submitBtn.style.display = 'block';
            } else {
                submitBtn.style.display = 'none';
            }
        });
    }
});

// Remove file from list
function removeFile(index) {
    const fileInput = document.getElementById('photoUpload');
    const fileList = document.getElementById('fileList');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!fileInput) return;
    
    // Create new FileList without the removed file
    const dt = new DataTransfer();
    const files = Array.from(fileInput.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    fileInput.files = dt.files;
    
    // Update preview
    fileList.innerHTML = '';
    if (fileInput.files.length > 0) {
        Array.from(fileInput.files).forEach((file, i) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-preview';
            fileItem.innerHTML = `
                <span class="file-name">${file.name}</span>
                <button type="button" class="remove-file" onclick="removeFile(${i})">×</button>
            `;
            fileList.appendChild(fileItem);
        });
        submitBtn.style.display = 'block';
    } else {
        submitBtn.style.display = 'none';
    }
}
