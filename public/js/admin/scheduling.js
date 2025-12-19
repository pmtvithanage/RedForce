const dutyPointsContainer = document.getElementById("dutyPointsContainer");
const dutyPointTemplate = document.getElementById("dutyPointTemplate");
const officersPopup = document.getElementById("officersPopup");
const newDutyPointPopup = document.getElementById("newDutyPointPopup");
const backdrop = document.getElementById("backdrop");
const newDutyPointForm = document.getElementById("newDutyPointForm");
const newOfficersList = document.getElementById("newOfficersList");

// Example data with more duty points
const dutyPointsData = [
  {
    supervisors: [
      { name: "Kamal Susantha", duty: "Day" },
      { name: "Samantha Hapugoda", duty: "Night" },
    ],
    officers: [
      {
        id: "PF416",
        name: "Kasun Silva",
        rank: "Level 4",
        duty: "Night",
        assignment: "Cargills (Ceylon) PLC",
        location: "No.40 York Street, Colombo 1",
        rating: 1399,
      },
      {
        id: "PF664",
        name: "Dilan Jayasuriya",
        rank: "Level 3",
        duty: "Day",
        assignment: "Aitken Spence PLC",
        location: "Colombo District, Colombo",
        rating: 1382,
      },
    ],
  },
  {
    supervisors: [
      { name: "Ruwan Perera", duty: "Day" },
      { name: "Nadeesha Wickramasinghe", duty: "Night" },
    ],
    officers: [
      {
        id: "PF220",
        name: "Chamika Bandara",
        rank: "Level 3",
        duty: "Day",
        assignment: "Sri Lanka Telecom PLC",
        location: "Colombo Corporate HQ",
        rating: 1380,
      },
    ],
  },
  {
    supervisors: [
      { name: "Mahesh Weerasinghe", duty: "Day" },
      { name: "Dilki Fernando", duty: "Night" },
    ],
    officers: [
      {
        id: "PF902",
        name: "Sajith Silva",
        rank: "Level 2",
        duty: "Day",
        assignment: "Dialog Axiata PLC",
        location: "Colombo HQ",
        rating: 1275,
      },
      {
        id: "PF811",
        name: "Kavindu Perera",
        rank: "Level 1",
        duty: "Night",
        assignment: "Keells Supermarket",
        location: "Nugegoda",
        rating: 1202,
      },
    ],
  },
];

// Available officers data
const availableOfficers = [
  {
    id: "PF231",
    name: "Nuwan Perera",
    rank: "OIC",
    status: "On Duty",
    assignment: "People's Bank PLC",
    rating: 1403,
  },
  {
    id: "PF416",
    name: " Kasun Silva",
    rank: "OIC",
    status: "On Leave",
    assignment: "Cargills PLC",
    rating: 1399,
  },
  {
    id: "PF664",
    name: " Dilan Jayasuriya",
    rank: "OIC",
    status: "On Duty",
    assignment: "Aitken Spence PLC",
    rating: 1382,
  },
  {
    id: "PF220",
    name: " Chamika Bandara",
    rank: "Level 4",
    status: "On Duty",
    assignment: "Sri Lanka Telecom",
    rating: 1380,
  },
  {
    id: "PF100",
    name: " Suranga Kumara",
    rank: "OIC",
    status: "On Duty",
    assignment: "Petroleum Corporation",
    rating: 1376,
  },
  {
    id: "PF230",
    name: "Tharindu Wickramasinghe",
    rank: "Level 3",
    status: "On Break",
    assignment: "People's Bank PLC",
    rating: 1373,
  },
  {
    id: "DE141",
    name: " Malinda Lacash",
    rank: "Level 4",
    status: "On Leave",
    assignment: "Mausloka Locatial",
    rating: 1370,
  },
];

// Track which element is being replaced
let currentReplacementTarget = null;
let replacementType = null; // 'supervisor' or 'officer'
let currentSupervisorDutyType = null; // For new duty point supervisor selection

// Render all duty points
function renderDutyPoints() {
  dutyPointsContainer.innerHTML = "";
  dutyPointsData.forEach((dutyPoint, index) => {
    const clone = dutyPointTemplate.content.cloneNode(true);

    // Title with number
    clone.querySelector(".duty-title").textContent = `Duty Point ${index + 1}`;

    // Fill supervisors
    const supervisorDivs = clone.querySelectorAll(".supervisor");
    dutyPoint.supervisors.forEach((sup, i) => {
      supervisorDivs[i].querySelector("input").value = sup.name;
      const badge = supervisorDivs[i].querySelector(".badge");
      badge.textContent = sup.duty;
      badge.className = `badge ${sup.duty.toLowerCase()}`;
    });

    // Fill officers
    const tbody = clone.querySelector("tbody");
    dutyPoint.officers.forEach((officer, i) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${i + 1}.</td>
        <td>${officer.id}</td>
        <td>${officer.name}</td>
        <td>${officer.rank}</td>
        <td><span class="badge ${officer.duty.toLowerCase()}">${
        officer.duty
      }</span></td>
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
}

// Initial render
renderDutyPoints();

// Show officers popup (with higher z-index to appear over new duty point popup)
function showOfficersPopup(target, type) {
  currentReplacementTarget = target;
  replacementType = type;
  officersPopup.style.display = "flex";
  officersPopup.style.zIndex = "1002"; // Higher than new duty point popup
  backdrop.style.zIndex = "1001"; // Between the two popups
  document.body.style.overflow = "hidden";
}

// Hide officers popup
function hideOfficersPopup() {
  officersPopup.style.display = "none";
  officersPopup.style.zIndex = "1000"; // Reset to default
  backdrop.style.zIndex = "999"; // Reset to default

  // Only hide backdrop if new duty point popup is also closed
  if (newDutyPointPopup.style.display === "none") {
    backdrop.hidden = true;
    document.body.style.overflow = "";
  }

  currentReplacementTarget = null;
  replacementType = null;
  currentSupervisorDutyType = null;
}

// Show new duty point popup
function showNewDutyPointPopup() {
  newDutyPointPopup.style.display = "flex";
  backdrop.hidden = false;
  document.body.style.overflow = "hidden";
  // Reset form
  newDutyPointForm.reset();
  newOfficersList.innerHTML = "";
  // Add one initial officer row
  addOfficerToNewDutyPoint();
}

// Hide new duty point popup
function hideNewDutyPointPopup() {
  newDutyPointPopup.style.display = "none";
  // Only hide backdrop if officers popup is also closed
  if (officersPopup.style.display === "none") {
    backdrop.hidden = true;
    document.body.style.overflow = "";
  }
}

// Add officer to new duty point form
function addOfficerToNewDutyPoint() {
  const officerIndex = newOfficersList.children.length;
  const officerItem = document.createElement("div");
  officerItem.className = "officer-item";
  officerItem.innerHTML = `
    <input type="text" placeholder="Officer ID" class="officer-id" value="PF${
      100 + officerIndex
    }">
    <input type="text" placeholder="Officer Name" class="officer-name" value="New Officer ${
      officerIndex + 1
    }">
    <input type="text" placeholder="Rank" class="officer-rank" value="Level 1">
    <select class="officer-duty">
      <option value="Day">Day</option>
      <option value="Night">Night</option>
    </select>
    <button type="button" class="remove-officer-btn">Remove</button>
  `;
  newOfficersList.appendChild(officerItem);
}

// Handle officer selection
function handleOfficerSelection(officer) {
  if (currentReplacementTarget && replacementType) {
    if (replacementType === "supervisor") {
      // For new duty point supervisor selection
      if (currentSupervisorDutyType) {
        const inputId =
          currentSupervisorDutyType === "day"
            ? "daySupervisor"
            : "nightSupervisor";
        document.getElementById(inputId).value = officer.name;
      } else {
        // Replace supervisor input value in existing duty point
        currentReplacementTarget.querySelector("input").value = officer.name;
      }
    } else if (replacementType === "officer") {
      // Replace officer row data
      const row = currentReplacementTarget;
      row.cells[1].textContent = officer.id;
      row.cells[2].textContent = officer.name;
      row.cells[3].textContent = officer.rank;
      // Keep the existing duty time badge
      row.cells[5].innerHTML = `${officer.assignment} <br><small>${
        row.cells[5].querySelector("small")?.textContent || "Location"
      }</small>`;
      row.cells[6].textContent = officer.rating;
    }
  }
  hideOfficersPopup();
}

// Handle Replace and Remove buttons (delegation)
document.body.addEventListener("click", (e) => {
  // Replace clicked - Supervisor
  if (
    e.target.classList.contains("replace-btn") &&
    e.target.closest(".supervisor")
  ) {
    const supervisorDiv = e.target.closest(".supervisor");
    showOfficersPopup(supervisorDiv, "supervisor");
  }

  // Replace clicked - Officer
  else if (
    e.target.classList.contains("replace-btn") &&
    e.target.closest("tr")
  ) {
    const officerRow = e.target.closest("tr");
    showOfficersPopup(officerRow, "officer");
  }

  // Remove clicked
  else if (e.target.classList.contains("remove-btn")) {
    const row = e.target.closest("tr");
    if (row) {
      const tbody = row.closest("tbody");
      row.remove();
      renumberOfficers(tbody);
    }
  }

  // Add Officer clicked
  else if (e.target.classList.contains("add-officer")) {
    const tbody = e.target.closest(".duty-point").querySelector("tbody");
    const newRow = document.createElement("tr");
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

  // Close popup - Officers popup
  else if (
    e.target.classList.contains("close-popup") &&
    e.target.closest("#officersPopup")
  ) {
    hideOfficersPopup();
  }

  // Close popup - New duty point popup
  else if (
    e.target.classList.contains("close-popup") &&
    e.target.closest("#newDutyPointPopup")
  ) {
    hideNewDutyPointPopup();
  }

  // Select officer from popup
  else if (e.target.classList.contains("select-officer")) {
    const row = e.target.closest("tr");
    const officerIndex = Array.from(row.parentNode.children).indexOf(row);
    const selectedOfficer = availableOfficers[officerIndex];
    handleOfficerSelection(selectedOfficer);
  }

  // Select supervisor for new duty point
  else if (e.target.classList.contains("select-supervisor-btn")) {
    currentSupervisorDutyType = e.target.dataset.duty;
    showOfficersPopup(null, "supervisor");
  }

  // Add officer to new duty point
  else if (e.target.classList.contains("add-officer-btn")) {
    addOfficerToNewDutyPoint();
  }

  // Remove officer from new duty point
  else if (e.target.classList.contains("remove-officer-btn")) {
    e.target.closest(".officer-item").remove();
  }

  // Cancel new duty point
  else if (e.target.classList.contains("cancel-btn")) {
    hideNewDutyPointPopup();
  }
});

// Close popup when clicking on backdrop
backdrop.addEventListener("click", (e) => {
  if (e.target === backdrop) {
    // Only close officers popup if it's open, otherwise close new duty point popup
    if (officersPopup.style.display === "flex") {
      hideOfficersPopup();
    } else {
      hideNewDutyPointPopup();
    }
  }
});

// Close popup with Escape key
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    // Close officers popup first if open, then new duty point popup
    if (officersPopup.style.display === "flex") {
      hideOfficersPopup();
    } else if (newDutyPointPopup.style.display === "flex") {
      hideNewDutyPointPopup();
    }
  }
});

// Function to renumber officers inside a table
function renumberOfficers(tbody) {
  tbody.querySelectorAll("tr").forEach((row, i) => {
    row.querySelector("td").textContent = `${i + 1}.`;
  });
}

// Create new duty point button
document
  .querySelector(".new-duty-btn")
  .addEventListener("click", showNewDutyPointPopup);

// Handle new duty point form submission
newDutyPointForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const dutyPointName =
    document.getElementById("dutyPointName").value ||
    `Duty Point ${dutyPointsData.length + 1}`;
  const daySupervisor =
    document.getElementById("daySupervisor").value || "Day Supervisor";
  const nightSupervisor =
    document.getElementById("nightSupervisor").value || "Night Supervisor";

  // Collect officers data
  const officers = Array.from(newOfficersList.children).map((item, index) => ({
    id: item.querySelector(".officer-id").value || `PF${100 + index}`,
    name:
      item.querySelector(".officer-name").value || `New Officer ${index + 1}`,
    rank: item.querySelector(".officer-rank").value || "Level 1",
    duty: item.querySelector(".officer-duty").value || "Day",
    assignment: "New Assignment",
    location: "New Location",
    rating: 1000 + index,
  }));

  // Create new duty point
  const newDutyPoint = {
    supervisors: [
      { name: daySupervisor, duty: "Day" },
      { name: nightSupervisor, duty: "Night" },
    ],
    officers: officers,
  };

  // Add to duty points data
  dutyPointsData.push(newDutyPoint);

  // Re-render duty points
  renderDutyPoints();

  // Hide popup and show success message
  hideNewDutyPointPopup();
  alert("Duty Point created successfully!");
});

// Example mobile riders data
const mobileRidersData = [
  {
    rider: "Kasun Samanta",
    sites: [
      {
        id: "zs416",
        location: "Cargills (Ceylon) PLC, No.40 York Street, Colombo 1",
      },
      { id: "zz664", location: "Aitken Spence PLC, Colombo District, Colombo" },
      { id: "zx220", location: "Sri Lanka Telecom PLC, Colombo Corporate HQ" },
    ],
  },
  {
    rider: "Kasun Samanta",
    sites: [
      {
        id: "zs416",
        location: "Cargills (Ceylon) PLC, No.40 York Street, Colombo 1",
      },
      { id: "zz664", location: "Aitken Spence PLC, Colombo District, Colombo" },
      { id: "zx220", location: "Sri Lanka Telecom PLC, Colombo Corporate HQ" },
    ],
  },
];

const mobileRidersContainer = document.getElementById("mobileRidersContainer");
const mobileRiderTemplate = document.getElementById("mobileRiderTemplate");

// Render mobile riders
mobileRidersData.forEach((riderData) => {
  const clone = mobileRiderTemplate.content.cloneNode(true);

  // Rider name
  clone.querySelector(".rider input").value = riderData.rider;

  // Fill sites
  const tbody = clone.querySelector("tbody");
  riderData.sites.forEach((site, i) => {
    const row = document.createElement("tr");
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
const riderSearchInput = document.getElementById("riderSearch");
const searchRiderBtn = document.getElementById("searchRiderBtn");

function renderMobileRiders(filteredData) {
  mobileRidersContainer.innerHTML = ""; // clear previous
  filteredData.forEach((riderData) => {
    const clone = mobileRiderTemplate.content.cloneNode(true);

    // Rider name
    clone.querySelector(".rider input").value = riderData.rider;

    // Fill sites
    const tbody = clone.querySelector("tbody");
    riderData.sites.forEach((site, i) => {
      const row = document.createElement("tr");
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
searchRiderBtn.addEventListener("click", () => {
  const query = riderSearchInput.value.toLowerCase();
  const filtered = mobileRidersData.filter((r) =>
    r.rider.toLowerCase().includes(query)
  );
  renderMobileRiders(filtered);
});
