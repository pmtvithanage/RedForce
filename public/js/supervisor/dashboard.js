// Supervisor Dashboard JS

const officers = [
  { name: 'Athula Adikari', status: 'present' },
  { name: 'Saman kumara', status: 'present' },
  { name: 'Amara bandu', status: 'present' },
  { name: 'wanidu Hasaranga', status: 'present' },
  { name: 'Amantha Perera', status: 'present' },
  { name: 'Samitha Vithana', status: 'present' }
];

function renderRows(list) {
  const tbody = document.getElementById('attendanceBody');
  if (!tbody) return;
  
  tbody.innerHTML = '';
  
  if (list.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="2" style="text-align: center; padding: 20px; color: #999;">
          No officers found
        </td>
      </tr>
    `;
    return;
  }
  
  list.forEach(({ name, status }) => {
    const tr = document.createElement('tr');
    const statusBadge = status === 'present'
      ? `<span class="status-badge present">Present</span>`
      : `<span class="status-badge absent">Absent</span>`;

    tr.innerHTML = `
      <td><a href="#" class="name-link">${name}</a></td>
      <td>${statusBadge}</td>
    `;
    tbody.appendChild(tr);
  });
}

function attachHandlers() {
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const term = e.target.value.toLowerCase().trim();
      const filtered = officers.filter(o => o.name.toLowerCase().includes(term));
      renderRows(filtered);
    });
  }
}

// Init
window.addEventListener('DOMContentLoaded', () => {
  renderRows(officers);
  attachHandlers();
});
