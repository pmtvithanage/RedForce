// Horizontal drag + wheel scroll for the ads rail
(function () {
  const rail = document.getElementById('adsRail');
  if (!rail) return;

  let isDown = false;
  let startX = 0;
  let scrollLeft = 0;

  const startDrag = (e) => {
    isDown = true;
    rail.classList.add('is-dragging');
    startX = (e.touches ? e.touches[0].pageX : e.pageX) - rail.offsetLeft;
    scrollLeft = rail.scrollLeft;
  };
  const endDrag = () => {
    isDown = false;
    rail.classList.remove('is-dragging');
  };
  const onMove = (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = (e.touches ? e.touches[0].pageX : e.pageX) - rail.offsetLeft;
    const walk = (x - startX) * 1; // multiplier for speed
    rail.scrollLeft = scrollLeft - walk;
  };

  
  rail.addEventListener('mousemove', onMove, { passive: false });
  rail.addEventListener('touchstart', startDrag, { passive: true });
  rail.addEventListener('touchend', endDrag, { passive: true });
  rail.addEventListener('touchmove', onMove, { passive: false });

  // Vertical wheel -> horizontal scroll
  rail.addEventListener('wheel', (e) => {
    if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
      rail.scrollLeft += e.deltaY;
      e.preventDefault();
    }
  }, { passive: false });
})();

// Image upload preview and simple form handling
(function () {
  const fileInput = document.getElementById('fileInput');
  const uploadBtn = document.getElementById('uploadBtn');
  const preview = document.getElementById('preview');
  const form = document.getElementById('createForm');
  const hint = document.getElementById('formHint');
  const uploader = document.getElementById('uploader');

  if (!fileInput || !preview || !form) return;

  uploadBtn?.addEventListener('click', () => fileInput.click());
  uploader?.addEventListener('click', (e) => {
    if (e.target === uploader || e.target === preview) fileInput.click();
  });

  fileInput.addEventListener('change', () => {
    const file = fileInput.files && fileInput.files[0];
    if (!file) return;
    const url = URL.createObjectURL(file);
    preview.innerHTML = '';
    const img = document.createElement('img');
    img.src = url;
    img.alt = 'Selected advertisement';
    preview.appendChild(img);
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const roles = Array.from(form.querySelectorAll('input[name="roles"]'))
      .filter((el) => el.checked)
      .map((el) => el.value);

    if (!fileInput.files || fileInput.files.length === 0) {
      hint.textContent = 'Please upload an image before publishing.';
      return;
    }
    if (roles.length === 0) {
      hint.textContent = 'Select at least one role.';
      return;
    }

    // Simulated publish
    hint.textContent = 'Published ✓';
    hint.style.color = '#059669';
    form.reset();
  });
})();


