// Photo preview logic
(function () {
  const photoInput = document.getElementById("photoInput");
  const photoPreview = document.getElementById("photoPreview");
  const form = document.getElementById("applicationForm");

  if (photoInput) {
    photoInput.addEventListener("change", () => {
      const file = photoInput.files && photoInput.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (e) => {
        photoPreview.src = String(e.target?.result || "");
        photoPreview.hidden = false;
      };
      reader.readAsDataURL(file);
    });
  }

  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("Submitted!");
    });
  }
})();

const cvInput = document.getElementById("cv");
const cvFileName = document.getElementById("cvFileName");

if (cvInput) {
  cvInput.addEventListener("change", () => {
    cvFileName.textContent = cvInput.files.length
      ? cvInput.files[0].name
      : "No file chosen";
  });
}
// Cancel button logic → Go back to previous page
function cancel() {
  window.history.back();
}
