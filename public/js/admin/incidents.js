// incidentsData will be populated from the server (AJAX)
let incidentsData = [];

// Fetch incidents from server and initialize the table
async function loadIncidentsFromServer() {
    const url = window.API_GET_INCIDENTS || '/Admin/getIncidents';
    try {
        const resp = await fetch(url, { credentials: 'same-origin' });
        const json = await resp.json();
        if (json && json.status === 'success' && Array.isArray(json.incidents)) {
            incidentsData = json.incidents;
        } else {
            console.warn('Unexpected response fetching incidents:', json);
            incidentsData = [];
        }
    } catch (err) {
        console.error('Failed to load incidents from server:', err);
        incidentsData = [];
    }

    // Render after data is loaded
    renderIncidentsTable(incidentsData);
}

// Function to get status class
function getStatusClass(status) {
    switch (status.toLowerCase()) {
        case 'resolved':
            return 'status-resolved';
        case 'in progress':
            return 'status-in-progress';
        case 'open':
            return 'status-open';
        case 'escalated':
            return 'status-escalated';
        case 'pending report':
            return 'status-pending';
        default:
            return 'status-open';
    }
}

// Function to render incidents table
function renderIncidentsTable(incidents) {
    const tableBody = document.getElementById('incidentsTableBody');
    tableBody.innerHTML = '';

    incidents.forEach((incident, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${incident.id}</td>
            <td>${incident.site}</td>
            <td>${incident.location}</td>
            <td>${incident.officer}</td>
            <td><span class="status-pill ${getStatusClass(incident.status)}">${incident.status}</span></td>
            <td>${incident.time}</td>
        `;
        
        // Add click event to open modal
        row.addEventListener('click', () => openIncidentModal(incident, index));
        tableBody.appendChild(row);
    });
}

// Function to filter incidents based on search term
function filterIncidents(searchTerm) {
    const filtered = incidentsData.filter(incident => {
        const searchLower = searchTerm.toLowerCase();
        return (
            incident.id.toLowerCase().includes(searchLower) ||
            incident.site.toLowerCase().includes(searchLower) ||
            incident.location.toLowerCase().includes(searchLower) ||
            incident.officer.toLowerCase().includes(searchLower) ||
            incident.status.toLowerCase().includes(searchLower) ||
            incident.time.toLowerCase().includes(searchLower)
        );
    });
    return filtered;
}

// Search functionality
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.trim();
        const filteredIncidents = filterIncidents(searchTerm);
        renderIncidentsTable(filteredIncidents);
    });
}

// Modal functionality
let currentEditingIndex = -1;

function openIncidentModal(incident, index) {
    currentEditingIndex = index;
    
    // Populate modal fields
    document.getElementById('modalIncidentId').value = incident.id;
    document.getElementById('modalSite').value = incident.site;
    document.getElementById('modalLocation').value = incident.location;
    document.getElementById('modalOfficer').value = incident.officer;
    // Only allow the three official statuses in the select. If incident.status is unknown, default to 'Open'.
    const allowedStatuses = ['Open', 'In Progress', 'Resolved'];
    const currentStatus = (incident.status || '').trim();
    document.getElementById('modalStatus').value = allowedStatuses.includes(currentStatus) ? currentStatus : 'Open';
    document.getElementById('modalTime').value = incident.time;
    document.getElementById('modalDescription').value = incident.description || '';
    document.getElementById('modalNotes').value = incident.notes || '';
    
    // Show modal
    document.getElementById('incidentModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('incidentModal').style.display = 'none';
    currentEditingIndex = -1;
}

function saveIncidentChanges() {
    if (currentEditingIndex === -1) return;
    
    // Get form values
    const form = document.getElementById('incidentForm');
    const formData = new FormData(form);

    const incidentId = formData.get('incidentId');
    const status = formData.get('status');

    // Basic validation
    if (!incidentId || !status) {
        showNotification('Please provide a valid status', 'error');
        return;
    }

    const updateUrl = window.API_UPDATE_INCIDENT || '/Admin/updateIncident';

    // Disable save button while saving
    const saveBtn = document.getElementById('saveBtn');
    if (saveBtn) saveBtn.disabled = true;

    fetch(updateUrl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json'
        },
        body: new URLSearchParams({
            incident_id: incidentId,
            status: status
        })
    })
    .then(r => r.json())
    .then(json => {
        if (json && json.status === 'success') {
            // Persist change locally and re-render
            incidentsData[currentEditingIndex] = Object.assign({}, incidentsData[currentEditingIndex], {
                id: incidentId,
                status: status,
                site: formData.get('site'),
                location: formData.get('location'),
                officer: formData.get('officer'),
                time: formData.get('time'),
                description: formData.get('description'),
                notes: formData.get('notes')
            });

            renderIncidentsTable(incidentsData);
            closeModal();
            showNotification('Incident updated successfully!', 'success');
        } else {
            console.error('Failed to update incident', json);
            showNotification(json.message || 'Failed to update incident', 'error');
        }
    })
    .catch(err => {
        console.error('Error updating incident', err);
        showNotification('Error updating incident', 'error');
    })
    .finally(() => {
        if (saveBtn) saveBtn.disabled = false;
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 600;
        z-index: 2000;
        animation: slideIn 0.3s ease-out;
        background-color: ${type === 'success' ? '#28a745' : '#007bff'};
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Initialize the dashboard
async function initializeDashboard() {
    // Load incidents from the server then wire up UI
    await loadIncidentsFromServer();
    initializeSearch();
    initializeModal();
}

function initializeModal() {
    const modal = document.getElementById('incidentModal');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveBtn = document.getElementById('saveBtn');
    
    // Close modal when clicking X
    closeBtn.addEventListener('click', closeModal);
    
    // Close modal when clicking Cancel
    cancelBtn.addEventListener('click', closeModal);
    
    // Save changes when clicking Save
    saveBtn.addEventListener('click', saveIncidentChanges);
    
    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    });
}

// Wait for DOM to be loaded
document.addEventListener('DOMContentLoaded', () => {
    // entrypoint
    initializeDashboard().catch(err => console.error('Failed to initialize dashboard', err));
});
