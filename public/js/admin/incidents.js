// Sample incident data
const incidentsData = [
    {
        id: '#IN2312',
        site: 'SLT - Z1232',
        location: 'No. 05, Dalada Veediya, Kandy',
        officer: '#IN2312',
        status: 'Resolved',
        time: '#IN2312',
        description: 'Security breach detected at main entrance. Unauthorized access attempt.',
        notes: 'Officer responded within 5 minutes. Situation resolved without incident.'
    },
    {
        id: '#IN2311',
        site: "People's Bank - Z2243",
        location: 'No 40, Station Rd, Weligama',
        officer: '#IN2312',
        status: 'Resolved',
        time: '#IN2312',
        description: 'Suspicious activity reported in parking area.',
        notes: 'False alarm - customer was retrieving items from vehicle.'
    },
    {
        id: '#IN2310',
        site: 'SLS Bank - Z2134',
        location: 'No 265 Ward Pl, Colombo 00700',
        officer: '#IN2312',
        status: 'In View',
        time: '#IN2312',
        description: 'ATM malfunction causing transaction issues.',
        notes: 'Technician scheduled for maintenance. Monitoring ongoing.'
    },
    {
        id: '#IN2309',
        site: "People's Bank - Z2256",
        location: 'No.75, Sir Chittampalam A. Gardiner Mawatha, Colombo',
        officer: '#IN2312',
        status: 'Escalated',
        time: '#IN2312',
        description: 'Fire alarm triggered in basement area.',
        notes: 'Emergency services contacted. Building evacuated safely.'
    },
    {
        id: '#IN2308',
        site: "People's Leasing - Z2314",
        location: 'No.1161, Maradana Road, Borella.',
        officer: '#IN2312',
        status: 'Resolved',
        time: '#IN2312',
        description: 'Power outage affecting security systems.',
        notes: 'Backup generators activated. All systems operational.'
    },
    {
        id: '#IN2307',
        site: 'USW - Z3288',
        location: '18, Norris Avenue, Colombo 00800',
        officer: '#IN2312',
        status: 'Pending Report',
        time: '#IN2312',
        description: 'Vandalism reported on exterior walls.',
        notes: 'Photos taken. Police report filed. Cleanup pending.'
    },
    {
        id: '#IN2306',
        site: "People's Bank - Z2214",
        location: 'No : 19 Kaduwela Road Battaramulla',
        officer: '#IN2312',
        status: 'Resolved',
        time: '#IN2312',
        description: 'Medical emergency in lobby area.',
        notes: 'Ambulance called. Patient transported to hospital.'
    }
];

// Function to get status class
function getStatusClass(status) {
    switch (status.toLowerCase()) {
        case 'resolved':
            return 'status-resolved';
        case 'in view':
            return 'status-inview';
        case 'escalated':
            return 'status-escalated';
        case 'pending report':
            return 'status-pending';
        default:
            return 'status-resolved';
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
    document.getElementById('modalStatus').value = incident.status;
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
    const formData = new FormData(document.getElementById('incidentForm'));
    
    // Update the incident data
    incidentsData[currentEditingIndex] = {
        id: formData.get('incidentId'),
        site: formData.get('site'),
        location: formData.get('location'),
        officer: formData.get('officer'),
        status: formData.get('status'),
        time: formData.get('time'),
        description: formData.get('description'),
        notes: formData.get('notes')
    };
    
    // Re-render the table to reflect changes
    renderIncidentsTable(incidentsData);
    
    // Close modal
    closeModal();
    
    // Show success message
    showNotification('Incident updated successfully!', 'success');
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
function initializeDashboard() {
    renderIncidentsTable(incidentsData);
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
document.addEventListener('DOMContentLoaded', initializeDashboard);
