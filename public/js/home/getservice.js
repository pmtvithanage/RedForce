// --- Photo preview logic ---
(function () {
  const photoInput = document.getElementById("photoInput");
  const photoPreview = document.getElementById("photoPreview");
  const form = document.getElementById("serviceForm");

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
      submitForm();
    });
  }
})();

// --- Cancel and Submit ---
function cancel() {
  history.back();
}

function submitForm() {
  const data = {
    companyName: v("company-name"),
    email: v("email"),
    phone: v("phone"),
    ownerName: v("owner-name"),
    logoFile: document.getElementById("photoInput").files[0]?.name || null,
    sites: [...document.querySelectorAll("#serviceTable tbody tr")].map(
      (r) => ({
        address: gv(r, ".site-address"),
        securityOfficers: +gv(r, ".security-officers") || 0,
        careTakers: +gv(r, ".care-takers") || 0,
        shiftType: gv(r, ".shift-type"),
      })
    ),
  };

  console.log("Form Data:", data);
  alert("Form submitted successfully! Check console for details.");
}

const v = (id) => document.getElementById(id).value.trim();
const gv = (row, sel) => row.querySelector(sel).value.trim();
