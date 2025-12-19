// Toggle Payment History Dropdown
const toggleBtn = document.getElementById('toggleBtn');
const historyContent = document.getElementById('historyContent');
const arrow = document.getElementById('arrow');

toggleBtn.addEventListener('click', () => {
  historyContent.classList.toggle('hidden');

  // Rotate arrow
  if (historyContent.classList.contains('hidden')) {
    arrow.textContent = '▼';
  } else {
    arrow.textContent = '▲';
  }
});

// Open popup when card is clicked
document.querySelectorAll('.card').forEach(card => {
  card.addEventListener('click', () => {
    const popupId = card.getAttribute('data-popup');
    document.getElementById(popupId).style.display = 'flex';
  });
});

// Close popup when X is clicked
document.querySelectorAll('.close').forEach(closeBtn => {
  closeBtn.addEventListener('click', () => {
    closeBtn.closest('.popup').style.display = 'none';
  });
});

// Close popup when clicking outside content
window.addEventListener('click', (e) => {
  document.querySelectorAll('.popup').forEach(popup => {
    if (e.target === popup) {
      popup.style.display = 'none';
    }
  });
});