// Supervisor Dashboard JS

// Store all attendance rows for filtering
let allAttendanceRows = [];

/**
 * Initialize attendance data from the table
 */
function initializeAttendanceData() {
  const tbody = document.getElementById('attendanceBody');
  if (!tbody) return;

  const rows = tbody.querySelectorAll('tr');
  allAttendanceRows = [];

  rows.forEach((row) => {
    const nameCell = row.querySelector('td:first-child');
    const statusCell = row.querySelector('td:last-child');
    
    if (nameCell && statusCell) {
      const nameLink = nameCell.querySelector('.name-link');
      const statusBadge = statusCell.querySelector('.status-badge');
      
      if (nameLink && statusBadge) {
        allAttendanceRows.push({
          name: nameLink.textContent.trim(),
          status: statusBadge.textContent.trim(),
          statusClass: statusBadge.className,
          rowHTML: row.outerHTML
        });
      }
    }
  });
}

/**
 * Render filtered attendance rows
 */
function renderRows(filteredList) {
  const tbody = document.getElementById('attendanceBody');
  if (!tbody) return;
  
  tbody.innerHTML = '';
  
  if (filteredList.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="2" style="text-align: center; padding: 20px; color: #999;">
          <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.3; display: block;"></i>
          No officers found matching your search
        </td>
      </tr>
    `;
    return;
  }
  
  filteredList.forEach((officer) => {
    tbody.insertAdjacentHTML('beforeend', officer.rowHTML);
  });
}

/**
 * Get status badge class based on status text
 */
function getStatusBadgeClass(status) {
  const statusLower = status.toLowerCase();
  if (statusLower === 'present') return 'status-badge present';
  if (statusLower === 'absent') return 'status-badge absent';
  if (statusLower === 'late') return 'status-badge late';
  if (statusLower === 'half day') return 'status-badge half-day';
  return 'status-badge present';
}

/**
 * Attach search handler
 */
function attachSearchHandler() {
  const searchInput = document.getElementById('searchInput');
  if (!searchInput) return;

  searchInput.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase().trim();
    
    if (searchTerm === '') {
      // Show all rows
      renderRows(allAttendanceRows);
    } else {
      // Filter rows by name
      const filtered = allAttendanceRows.filter(officer => 
        officer.name.toLowerCase().includes(searchTerm)
      );
      renderRows(filtered);
    }
  });
}

/**
 * Initialize dashboard
 */
window.addEventListener('DOMContentLoaded', () => {
  initializeAttendanceData();
  attachSearchHandler();
});
