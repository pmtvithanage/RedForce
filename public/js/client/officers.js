// Store original data for filtering
let originalData = [];
let currentData = [];
let currentRating = 0;

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Get data from PHP
    if (typeof guardsData !== 'undefined') {
        originalData = [...guardsData];
        currentData = [...originalData];
    }
    
    console.log('Officers page loaded');
    console.log('Guards data:', originalData);
});

function filterBySite() {
    const siteFilter = document.getElementById('siteFilter').value;
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    
    let filteredData = [...originalData];
    
    console.log('Filtering by site:', siteFilter);
    
    // Apply site filter
    if (siteFilter && siteFilter !== '' && siteFilter !== 'All Sites') {
        filteredData = filteredData.filter(guard => 
            guard.site === siteFilter
        );
    }
    
    // Apply search filter
    if (searchInput) {
        filteredData = filteredData.filter(guard =>
            guard.name.toLowerCase().includes(searchInput) ||
            guard.id.toLowerCase().includes(searchInput) ||
            guard.rank.toLowerCase().includes(searchInput) ||
            guard.status.toLowerCase().includes(searchInput) ||
            guard.site.toLowerCase().includes(searchInput)
        );
    }
    
    currentData = filteredData;
    renderTable(currentData);
    updateGuardsCount(currentData.length);
}

function searchGuards() {
    console.log('Search function called');
    filterBySite(); // This will apply both filters
}

function renderTable(data) {
    const tbody = document.getElementById('guardsTableBody');
    if (!tbody) {
        console.error('Table body not found');
        return;
    }
    
    tbody.innerHTML = '';
    
    data.forEach(guard => {
        const row = document.createElement('tr');
        row.className = 'guard-row';
        row.onclick = () => openGuardModal(guard.id, guard.name, guard.rank, guard.status, guard.site);
        
        row.innerHTML = `
            <td>
                <span class="officer-id">${escapeHtml(guard.id)}</span>
            </td>
            <td>
                <div class="officer-info">
                    <div class="officer-avatar">
                        ${guard.name.charAt(0).toUpperCase()}
                    </div>
                    <span class="officer-name">${escapeHtml(guard.name)}</span>
                </div>
            </td>
            <td>
                <span class="rank-badge rank-${guard.rank.toLowerCase()}">
                    ${escapeHtml(guard.rank)}
                </span>
            </td>
            <td>
                <span class="status-badge status-${guard.status.toLowerCase().replace(' ', '-')}">
                    ${escapeHtml(guard.status)}
                </span>
            </td>
            <td>
                <span class="site-info">${escapeHtml(guard.site)}</span>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

function updateGuardsCount(count) {
    const countElement = document.getElementById('guardsCount');
    if (countElement) {
        countElement.textContent = `${count} guards found`;
    }
}

function openGuardModal(id, name, rank, status, site) {
    console.log('Opening modal for:', name);
    
    // Update modal content
    document.getElementById('modalGuardId').textContent = id;
    document.getElementById('modalGuardName').textContent = name;
    document.getElementById('modalGuardRank').textContent = rank;
    document.getElementById('modalGuardSite').textContent = site;
    
    // Update avatar
    document.getElementById('modalGuardAvatar').textContent = name.charAt(0).toUpperCase();
    
    // Update status indicator
    const statusIndicator = document.getElementById('statusIndicator');
    statusIndicator.className = 'status-indicator';
    if (status.toLowerCase() === 'off duty') {
        statusIndicator.classList.add('off-duty');
    } else if (status.toLowerCase() === 'on break') {
        statusIndicator.classList.add('on-break');
    }
    
    // Reset rating
    currentRating = 0;
    updateStarRating(0);
    document.getElementById('ratingInput').value = '';
    
    // Show modal
    document.getElementById('guardModal').style.display = 'block';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId || 'guardModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function setRating(rating) {
    console.log('Setting rating:', rating);
    currentRating = rating;
    updateStarRating(rating);
}

function updateStarRating(rating) {
    const stars = document.querySelectorAll('.star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

function saveEvaluation() {
    const ratingText = document.getElementById('ratingInput').value;
    
    if (currentRating === 0) {
        alert('Please select a star rating.');
        return;
    }
    
    if (!ratingText.trim()) {
        alert('Please enter evaluation comments.');
        return;
    }
    
    console.log('Saving evaluation:', { rating: currentRating, text: ratingText });
    
    // Here you would normally send the evaluation to the server
    alert(`Evaluation saved!\nRating: ${currentRating} stars\nComments: ${ratingText}`);
    
    // Close modal
    closeModal('guardModal');
}

function exportData() {
    console.log('Exporting data');
    
    if (!currentData || currentData.length === 0) {
        alert('No data to export');
        return;
    }
    
    // Simple CSV export functionality
    let csv = 'Officer ID,Officer Name,Rank,Status,Site\n';
    
    currentData.forEach(guard => {
        csv += `"${guard.id}","${guard.name}","${guard.rank}","${guard.status}","${guard.site}"\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'guards_data.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('guardModal');
    if (modal && event.target === modal) {
        modal.style.display = 'none';
    }
}

// Make functions globally available
window.filterBySite = filterBySite;
window.searchGuards = searchGuards;
window.openGuardModal = openGuardModal;
window.closeModal = closeModal;
window.setRating = setRating;
window.saveEvaluation = saveEvaluation;
window.exportData = exportData;