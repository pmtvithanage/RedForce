// Supervisor Dashboard JS

const officers = [
  { name: 'John Smith', status: 'present' },
  { name: 'Sarah Johnson', status: 'present' },
  { name: 'Mitchell Brown', status: 'present' },
  { name: 'Jennifer Thompson', status: 'present' },
  { name: 'David Martinez', status: 'present' },
  { name: 'Lisa Anderson', status: 'present' }
];

function renderRows(list) {
  const tbody = document.getElementById('attendanceBody');
  tbody.innerHTML = '';
  list.forEach(({ name, status }) => {
    const tr = document.createElement('tr');
    const statusChip = status === 'present'
      ? `<span class="status-chip status-present"><i class="fas fa-circle-check"></i> Present</span>`
      : `<span class="status-chip status-absent"><i class="fas fa-circle-xmark"></i> Absent</span>`;

    tr.innerHTML = `
      <td>${name}</td>
      <td>${statusChip}</td>
      <td class="action-icons">
        <i class="fas fa-check"></i>
        <i class="fas fa-times fail"></i>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

function attachHandlers() {
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', () => {
      const term = searchInput.value.toLowerCase();
      const filtered = officers.filter(o => o.name.toLowerCase().includes(term));
      renderRows(filtered);
    });
  }

  const scanBtn = document.getElementById('scanBtn');
  if (scanBtn) {
    scanBtn.addEventListener('click', () => {
      alert('Opening QR Scanner... (placeholder)');
    });
  }
}

// Init
window.addEventListener('DOMContentLoaded', () => {
  renderRows(officers);
  attachHandlers();
});
