const addImageBtn = document.getElementById("addImageBtn");
const removeImageBtn = document.getElementById("removeImageBtn");
const imagePlaceholder = document.getElementById("imagePlaceholder");

let inputPath = document.querySelector("#image");
let file;

// Get the default image path from data attribute
const defaultImagePath = imagePlaceholder.getAttribute('data-default-src');

function toggleBrowse(){
    inputPath.click();
}

function removeImage(){
    addImageBtn.style.display = "block";
    removeImageBtn.style.display = "none";
    imagePlaceholder.style.display = "block"; // Changed to "block" to show default image

    // Reset to default image
    imagePlaceholder.setAttribute('src', defaultImagePath);

    inputPath.value = null;
    file = null;
}

inputPath.addEventListener('change', function(){
    file = this.files[0];

    if (file) {
        addImageBtn.style.display = "none";
        removeImageBtn.style.display = "block";
        imagePlaceholder.style.display = "block";
        showImage();
    } else {
        // If user cancels file selection, reset to default
        removeImage();
    }
});

function showImage(){
    let fileType = file.type;
    let validExtensions = ["image/jpeg", "image/jpg", "image/png"];

    if(validExtensions.includes(fileType)){
        let fileReader = new FileReader();

        fileReader.onload = () => {
            let fileURL = fileReader.result;
            imagePlaceholder.setAttribute('src', fileURL);
        }

        fileReader.onerror = () => {
            alert('Error reading file');
            removeImage();
        }

        fileReader.readAsDataURL(file);
    }
    else{
        alert('This is not a valid image file'); // Fixed typo
        removeImage();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize with default image visible
    imagePlaceholder.style.display = "block";
    imagePlaceholder.setAttribute('src', defaultImagePath);
});