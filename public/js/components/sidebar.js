(function () {
  const body = document.body;
  const toggle = document.getElementById('menuToggle');
  const backdrop = document.getElementById('backdrop');

  // Sidebar Open/Close
  function setOpen(isOpen) {
    body.classList.toggle('sidebar-open', isOpen);
    toggle.setAttribute('aria-expanded', String(isOpen));
    backdrop.hidden = !isOpen;
  }

  toggle.addEventListener('click', function () {
    setOpen(!body.classList.contains('sidebar-open'));
  });

  backdrop.addEventListener('click', function () {
    setOpen(false);
  });

  // Close sidebar on ESC
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      setOpen(false);
      closeProfileDropdown();
    }
  });

  // ---------------------------
  // Profile Dropdown Handling
  // ---------------------------
  const profileToggle = document.getElementById("profileToggle");
  const profileDropdown = document.getElementById("profileDropdown");

  function toggleProfileDropdown() {
    const isVisible = profileDropdown.style.display === "flex";
    profileDropdown.style.display = isVisible ? "none" : "flex";
  }

  function closeProfileDropdown() {
    profileDropdown.style.display = "none";
  }

  if (profileToggle) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation(); // prevent closing immediately
      toggleProfileDropdown();
    });
  }

  // Close dropdown when clicking outside
  document.addEventListener("click", (event) => {
    if (
      profileDropdown &&
      !profileToggle.contains(event.target) &&
      !profileDropdown.contains(event.target)
    ) {
      closeProfileDropdown();
    }
  });

  // Change Password Modal
const passwordModal = document.getElementById("passwordModal");
const changePasswordBtn = document.querySelector(".dropdown-link.change-password");
const cancelPasswordBtn = document.getElementById("cancelPasswordBtn");

function openPasswordModal() {
  passwordModal.hidden = false; // show modal
}

function closePasswordModal() {
  passwordModal.hidden = true; // hide modal
}

if (changePasswordBtn) {
  changePasswordBtn.addEventListener("click", (e) => {
    e.preventDefault();        // prevent page reload
    closeProfileDropdown();    // close dropdown first
    openPasswordModal();       // show modal ONLY on button click
  });
}

cancelPasswordBtn.addEventListener("click", closePasswordModal);

// Close modal when clicking outside
passwordModal.addEventListener("click", (e) => {
  if (e.target === passwordModal) closePasswordModal();
});

// Close modal on ESC
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") closePasswordModal();
});
})();

