const dutyPointsContainer = document.getElementById('dutyPointsContainer');
const dutyPointTemplate = document.getElementById('dutyPointTemplate');

// Example data with more duty points
const dutyPointsData = [
  {
    supervisors: [
      { name: "Kamal Susantha", duty: "Day" },
      { name: "Samantha Hapugoda", duty: "Night" }
    ],
    officers: [
      { id: "PF416", name: "Kasun Silva", rank: "Level 4", duty: "Night", assignment: "Cargills (Ceylon) PLC", location: "No.40 York Street, Colombo 1", rating: 1399 },
      { id: "PF664", name: "Dilan Jayasuriya", rank: "Level 3", duty: "Day", assignment: "Aitken Spence PLC", location: "Colombo District, Colombo", rating: 1382 }
    ]
  },
  {
    supervisors: [
      { name: "Ruwan Perera", duty: "Day" },
      { name: "Nadeesha Wickramasinghe", duty: "Night" }
    ],
    officers: [
      { id: "PF220", name: "Chamika Bandara", rank: "Level 3", duty: "Day", assignment: "Sri Lanka Telecom PLC", location: "Colombo Corporate HQ", rating: 1380 }
    ]
  },
  {
    supervisors: [
      { name: "Mahesh Weerasinghe", duty: "Day" },
      { name: "Dilki Fernando", duty: "Night" }
    ],
    officers: [
      { id: "PF902", name: "Sajith Silva", rank: "Level 2", duty: "Day", assignment: "Dialog Axiata PLC", location: "Colombo HQ", rating: 1275 },
      { id: "PF811", name: "Kavindu Perera", rank: "Level 1", duty: "Night", assignment: "Keells Supermarket", location: "Nugegoda", rating: 1202 }
    ]
  }
];

// Render all duty points
dutyPointsData.forEach((dutyPoint, index) => {
  const clone = dutyPointTemplate.content.cloneNode(true);

  // Title with number
  clone.querySelector('.duty-title').textContent = `Duty Point ${index + 1}`;

  // Fill supervisors
  const supervisorDivs = clone.querySelectorAll('.supervisor');
  dutyPoint.supervisors.forEach((sup, i) => {
    supervisorDivs[i].querySelector('input').value = sup.name;
    const badge = supervisorDivs[i].querySelector('.badge');
    badge.textContent = sup.duty;
    badge.className = `badge ${sup.duty.toLowerCase()}`;
  });

  // Fill officers
  const tbody = clone.querySelector('tbody');
  dutyPoint.officers.forEach((officer, i) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${i + 1}.</td>
      <td>${officer.id}</td>
      <td>${officer.name}</td>
      <td>${officer.rank}</td>
      <td><span class="badge ${officer.duty.toLowerCase()}">${officer.duty}</span></td>
      <td>${officer.assignment} <br><small>${officer.location}</small></td>
      <td>${officer.rating}</td>
      <td>
        <button class="replace-btn">Replace</button>
        <button class="remove-btn">Remove</button>
      </td>
    `;
    tbody.appendChild(row);
  });

  dutyPointsContainer.appendChild(clone);
});

// Handle Replace and Remove buttons (delegation)
document.body.addEventListener('click', (e) => {
  // Replace clicked
  if (e.target.classList.contains('replace-btn')) {
    alert("Replace clicked!");
  }

  // Remove clicked
  if (e.target.classList.contains('remove-btn')) {
    const row = e.target.closest('tr');
    if (row) {
      const tbody = row.closest('tbody');
      row.remove();
      renumberOfficers(tbody);
    }
  }

  // Add Officer clicked
  if (e.target.classList.contains('add-officer')) {
    const tbody = e.target.closest('.duty-point').querySelector('tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
      <td>?</td>
      <td>PFXXX</td>
      <td>New Officer</td>
      <td>Level 1</td>
      <td><span class="badge day">Day</span></td>
      <td>New Assignment <br><small>New Location</small></td>
      <td>1000</td>
      <td>
        <button class="replace-btn">Replace</button>
        <button class="remove-btn">Remove</button>
      </td>
    `;
    tbody.appendChild(newRow);
    renumberOfficers(tbody);
  }
});

// Function to renumber officers inside a table
function renumberOfficers(tbody) {
  tbody.querySelectorAll('tr').forEach((row, i) => {
    row.querySelector('td').textContent = `${i + 1}.`;
  });
}

// Create new duty point button
document.querySelector('.new-duty-btn').addEventListener('click', () => {
  alert("New Duty Point creation triggered!");
});

// Example mobile riders data
const mobileRidersData = [
  {
    rider: "Kasun Samanta",
    sites: [
      { id: "zs416", location: "Cargills (Ceylon) PLC, No.40 York Street, Colombo 1" },
      { id: "zz664", location: "Aitken Spence PLC, Colombo District, Colombo" },
      { id: "zx220", location: "Sri Lanka Telecom PLC, Colombo Corporate HQ" }
    ]
  },
  {
    rider: "Kasun Samanta",
    sites: [
      { id: "zs416", location: "Cargills (Ceylon) PLC, No.40 York Street, Colombo 1" },
      { id: "zz664", location: "Aitken Spence PLC, Colombo District, Colombo" },
      { id: "zx220", location: "Sri Lanka Telecom PLC, Colombo Corporate HQ" }
    ]
  }
];

const mobileRidersContainer = document.getElementById('mobileRidersContainer');
const mobileRiderTemplate = document.getElementById('mobileRiderTemplate');

// Render mobile riders
mobileRidersData.forEach(riderData => {
  const clone = mobileRiderTemplate.content.cloneNode(true);

  // Rider name
  clone.querySelector('.rider input').value = riderData.rider;

  // Fill sites
  const tbody = clone.querySelector('tbody');
  riderData.sites.forEach((site, i) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${i + 1}.</td>
      <td>${site.id}</td>
      <td>${site.location}</td>
    `;
    tbody.appendChild(row);
  });

  mobileRidersContainer.appendChild(clone);
});

// Rider search functionality
const riderSearchInput = document.getElementById('riderSearch');
const searchRiderBtn = document.getElementById('searchRiderBtn');

function renderMobileRiders(filteredData) {
  mobileRidersContainer.innerHTML = ''; // clear previous
  filteredData.forEach(riderData => {
    const clone = mobileRiderTemplate.content.cloneNode(true);

    // Rider name
    clone.querySelector('.rider input').value = riderData.rider;

    // Fill sites
    const tbody = clone.querySelector('tbody');
    riderData.sites.forEach((site, i) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${i + 1}.</td>
        <td>${site.id}</td>
        <td>${site.location}</td>
      `;
      tbody.appendChild(row);
    });

    mobileRidersContainer.appendChild(clone);
  });
}

// Initial render
renderMobileRiders(mobileRidersData);

// Search by rider name
searchRiderBtn.addEventListener('click', () => {
  const query = riderSearchInput.value.toLowerCase();
  const filtered = mobileRidersData.filter(r =>
    r.rider.toLowerCase().includes(query)
  );
  renderMobileRiders(filtered);
});