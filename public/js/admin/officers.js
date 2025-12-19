// ===== Sample Datasets =====
const datasets = {
  officers: [
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
      name: "Kasun Silva",
      rank: "OIC",
      status: "On Leave",
      assignment: "Cargills PLC",
      rating: 1399,
    },
    {
      id: "PF664",
      name: "Dilan Jayasuriya",
      rank: "OIC",
      status: "On Duty",
      assignment: "Aitken Spence PLC",
      rating: 1382,
    },
    {
      id: "PF220",
      name: "Chamika Bandara",
      rank: "Level 4",
      status: "On Duty",
      assignment: "Sri Lanka Telecom",
      rating: 1380,
    },
    {
      id: "PF100",
      name: "Suranga Kumara",
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
      id: "PF141",
      name: "Malinda Herath",
      rank: "Level 4",
      status: "On Leave",
      assignment: "Nawaloka Hospital",
      rating: 1370,
    },
    {
      id: "PF348",
      name: "Ruwan Gamage",
      rank: "OIC",
      status: "On Duty",
      assignment: "Durdans Hospital",
      rating: 1363,
    },
    {
      id: "PF552",
      name: "Janith Peiris",
      rank: "Level 3",
      status: "On Duty",
      assignment: "Commercial Bank HQ",
      rating: 1358,
    },
    {
      id: "PF687",
      name: "Amal Rathnayake",
      rank: "OIC",
      status: "On Break",
      assignment: "Sampath Bank PLC",
      rating: 1347,
    },
  ],
  mobileRiders: [
    {
      id: "MR101",
      name: "Sanjaya Peris",
      vehicle: "Bike",
      status: "On Duty",
      rating: 1201,
    },
    {
      id: "MR102",
      name: "Ramesh Nuwan",
      vehicle: "Scooter",
      status: "On Leave",
      rating: 1195,
    },
    {
      id: "MR103",
      name: "Pradeep Silva",
      vehicle: "Bike",
      status: "On Break",
      rating: 1187,
    },
    {
      id: "MR104",
      name: "Thushara Bandara",
      vehicle: "Motorbike",
      status: "On Duty",
      rating: 1179,
    },
    {
      id: "MR105",
      name: "Isuru Fernando",
      vehicle: "Three Wheeler",
      status: "On Duty",
      rating: 1165,
    },
    {
      id: "MR106",
      name: "Pasindu Jayawardena",
      vehicle: "Scooter",
      status: "On Leave",
      rating: 1154,
    },
    {
      id: "MR107",
      name: "Lakshan Dissanayake",
      vehicle: "Bike",
      status: "On Duty",
      rating: 1149,
    },
    {
      id: "MR108",
      name: "Viraj Perera",
      vehicle: "Motorbike",
      status: "On Break",
      rating: 1142,
    },
  ],
  careTakers: [
    {
      id: "CT501",
      name: "Lahiru Weerasinghe",
      shift: "Morning",
      status: "On Duty",
    },
    {
      id: "CT502",
      name: "Nadeesha Priyani",
      shift: "Evening",
      status: "On Leave",
    },
    {
      id: "CT503",
      name: "Manoj Rajapaksha",
      shift: "Night",
      status: "On Duty",
    },
    {
      id: "CT504",
      name: "Ravindu Gunasekara",
      shift: "Morning",
      status: "On Duty",
    },
    {
      id: "CT505",
      name: "Dilini Wickramasinghe",
      shift: "Evening",
      status: "On Leave",
    },
    {
      id: "CT506",
      name: "Chamari Jayasinghe",
      shift: "Night",
      status: "On Break",
    },
    {
      id: "CT507",
      name: "Kavindu Senarath",
      shift: "Morning",
      status: "On Duty",
    },
    {
      id: "CT508",
      name: "Anjali Ratnayake",
      shift: "Evening",
      status: "On Duty",
    },
  ],
};

// ===== Applications Data =====
const applicationsData = [
  {
    name: "I.W.Karunarathne",
    nic: "19937901880",
    mobile: "0775621231",
    date: "2025/08/10 10:28",
    cv: "cv.PDF",
  },
  {
    name: "G.H.Perera",
    nic: "20013901880",
    mobile: "076621341",
    date: "2025/08/10 11:42",
    cv: "cv.PDF",
  },
  {
    name: "K.D.Jayakody",
    nic: "19957361550",
    mobile: "0775621231",
    date: "2025/08/10 12:04",
    cv: "cv.PDF",
  },
];

// ===== Modal Elements =====
const officerModal = document.getElementById("officerModal");
const applicationsModal = document.getElementById("applicationsModal");
const ratingHistoryModal = document.getElementById("ratingHistoryModal");
const recruitmentModal = document.getElementById("recruitmentModal");

// ===== Modal Helpers =====
function openModal(modal) {
  modal.style.display = "flex";
  modal.classList.add("show");
}

function closeModal(modal) {
  modal.style.display = "none";
  modal.classList.remove("show");
}

// ===== Recruitment Modal Functions =====
function openRecruitmentModal() {
  openModal(recruitmentModal);
}

function closeRecruitmentModal() {
  closeModal(recruitmentModal);
}

function initializeRecruitmentModal() {
  const qualificationsList = document.getElementById("qualificationsList");
  const addQualificationBtn = document.getElementById("addQualificationBtn");
  const qualificationInput = document.getElementById("qualificationInput");
  const publishBtn = document.getElementById("publishRecruitmentBtn");
  const recruitmentTypeSelect = document.getElementById("recruitmentType");

  // Add qualification
  addQualificationBtn.addEventListener("click", function () {
    const qualification = qualificationInput.value.trim();
    if (qualification) {
      const li = document.createElement("li");
      li.innerHTML = `
        ${qualification}
        <button class="remove-qualification" type="button">×</button>
      `;
      qualificationsList.appendChild(li);
      qualificationInput.value = "";

      // Add remove functionality
      li.querySelector(".remove-qualification").addEventListener(
        "click",
        function () {
          li.remove();
        }
      );
    }
  });

  // Add qualification on Enter key
  qualificationInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      addQualificationBtn.click();
    }
  });

  // Publish recruitment
  publishBtn.addEventListener("click", function () {
    const recruitmentType = recruitmentTypeSelect.value;
    const description = document.querySelector(".description-textarea").value;
    const qualifications = Array.from(qualificationsList.children).map((li) =>
      li.textContent.replace("×", "").trim()
    );
    const publisher = document.getElementById("publisherInput").value;

    if (
      !recruitmentType ||
      !description ||
      qualifications.length === 0 ||
      !publisher
    ) {
      alert("Please fill in all fields and add at least one qualification.");
      return;
    }

    // Here you would typically send this data to your backend
    console.log("Publishing recruitment:", {
      type: recruitmentType,
      description,
      qualifications,
      publisher,
    });

    // Get the display name for the alert
    const typeDisplayNames = {
      "premise-officer": "Premise Officer",
      "care-taker": "Care Taker",
      "mobile-rider": "Mobile Rider",
    };

    alert(
      `${typeDisplayNames[recruitmentType]} recruitment published successfully!`
    );
    closeRecruitmentModal();

    // Reset form
    recruitmentTypeSelect.value = "";
    document.querySelector(".description-textarea").value = "";
    qualificationsList.innerHTML = "";
    document.getElementById("publisherInput").value = "";
  });
}

// ===== Applications Modal Functions =====
function openApplicationsModal() {
  openModal(applicationsModal);
}

function closeApplicationsModal() {
  closeModal(applicationsModal);
}

function handleAddApplication(applicantName) {
  // Here you would typically send this to your backend
  console.log(`Adding applicant: ${applicantName}`);
  alert(`Applicant ${applicantName} added successfully!`);

  // Remove the application from the list (optional)
  const applicationItem = document.querySelector(
    `.application-item:has(h4:contains("${applicantName}"))`
  );
  if (applicationItem) {
    applicationItem.remove();
  }

  // Update applications count
  updateApplicationsCount();
}

function updateApplicationsCount() {
  const remainingApplications =
    document.querySelectorAll(".application-item").length;
  const appsButton = document.querySelector(".btn.apps");
  if (appsButton) {
    appsButton.textContent = `${remainingApplications} Applications`;
  }
}

// ===== Initialize Applications Modal =====
function initializeApplicationsModal() {
  const applicationsList = document.querySelector(".applications-list");
  if (applicationsList) {
    applicationsList.innerHTML = applicationsData
      .map(
        (app) => `
      <div class="application-item">
        <div class="applicant-info">
          <h4>${app.name}</h4>
          <p>NIC: ${app.nic}</p>
          <p>Mobile No: ${app.mobile}</p>
        </div>
        <div class="application-actions">
          <div class="pdf-badge">PDF</div>
          <div class="cv-file">${app.cv}</div>
          <div class="application-date">${app.date}</div>
          <button class="btn add-small" data-applicant="${app.name}">Add</button>
        </div>
      </div>
    `
      )
      .join("");
  }

  // Add event listeners for Add buttons
  document.querySelectorAll(".btn.add-small").forEach((btn) => {
    btn.addEventListener("click", function () {
      const applicantName = this.getAttribute("data-applicant");
      handleAddApplication(applicantName);
    });
  });
}

// ===== Render Tables =====
function renderTable(tab, data) {
  const map = {
    officers: "officerTable",
    mobileRiders: "riderTable",
    careTakers: "caretakerTable",
  };
  const tbody = document.getElementById(map[tab]);
  if (!tbody) return;
  tbody.innerHTML = "";

  data.forEach((row, i) => {
    let statusClass =
      row.status === "On Duty"
        ? "on-duty"
        : row.status === "On Leave"
        ? "on-leave"
        : row.status === "On Break"
        ? "on-break"
        : "";

    if (tab === "officers") {
      tbody.insertAdjacentHTML(
        "beforeend",
        `
        <tr>
          <td>${i + 1}</td><td>${row.id}</td><td>${row.name}</td>
          <td>${row.rank}</td>
          <td><span class="status ${statusClass}">${row.status}</span></td>
          <td>${row.assignment}</td><td>${row.rating}</td>
        </tr>`
      );
    }
    if (tab === "mobileRiders") {
      tbody.insertAdjacentHTML(
        "beforeend",
        `
        <tr>
          <td>${i + 1}</td><td>${row.id}</td><td>${row.name}</td>
          <td>${row.vehicle}</td>
          <td><span class="status ${statusClass}">${row.status}</span></td>
          <td>${row.rating}</td>
        </tr>`
      );
    }
    if (tab === "careTakers") {
      tbody.insertAdjacentHTML(
        "beforeend",
        `
        <tr>
          <td>${i + 1}</td><td>${row.id}</td><td>${row.name}</td>
          <td>${row.shift}</td>
          <td><span class="status ${statusClass}">${row.status}</span></td>
        </tr>`
      );
    }
  });
}

function fillModal(record) {
  document.getElementById("officerName").textContent = record.name || "-";
  document.getElementById("officerId").textContent = record.id || "-";
  document.getElementById("officerRank").textContent =
    record.rank || record.vehicle || record.shift || "-";
  document.getElementById("officerLocation").textContent =
    record.assignment || "-";
  document.getElementById("officerRating").textContent = record.rating || "-";
}

// ===== Event Listeners =====
document.addEventListener("DOMContentLoaded", function () {
  // Initialize modals
  initializeApplicationsModal();
  initializeRecruitmentModal();

  // Add Officers button event
  document
    .getElementById("addOfficersBtn")
    .addEventListener("click", openApplicationsModal);

  // Open Recruitment button event
  document
    .getElementById("openRecruitmentBtn")
    .addEventListener("click", openRecruitmentModal);

  // Close Applications Modal
  document
    .getElementById("closeApplicationsModal")
    .addEventListener("click", closeApplicationsModal);
  applicationsModal.addEventListener("click", (e) => {
    if (e.target === applicationsModal || e.target.hasAttribute("data-close"))
      closeApplicationsModal();
  });

  // Close Recruitment Modal
  document
    .getElementById("closeRecruitmentModal")
    .addEventListener("click", closeRecruitmentModal);
  recruitmentModal.addEventListener("click", (e) => {
    if (e.target === recruitmentModal || e.target.hasAttribute("data-close"))
      closeRecruitmentModal();
  });

  // Row Click -> Officer Modal
  document.addEventListener("click", (e) => {
    const tr = e.target.closest("tbody tr");
    if (!tr) return;

    const wrapper = tr.closest(".tab-content");
    if (!wrapper || wrapper.hidden) return;

    let tabKey = wrapper.id; // officers | mobileRiders | careTakers
    const recId = tr.cells[1]?.innerText.trim();
    const record = (datasets[tabKey] || []).find((r) => r.id === recId);
    if (!record) return;

    fillModal(record);
    openModal(officerModal);
  });

  // Close Officer Modal
  document
    .getElementById("closeModal")
    .addEventListener("click", () => closeModal(officerModal));
  officerModal.addEventListener("click", (e) => {
    if (e.target === officerModal || e.target.hasAttribute("data-close"))
      closeModal(officerModal);
  });

  // Escape key to close modals
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeModal(officerModal);
      closeModal(applicationsModal);
      closeModal(ratingHistoryModal);
      closeModal(recruitmentModal);
    }
  });

  // Tabs
  let currentTab = "officers";
  renderTable(currentTab, datasets[currentTab]);

  document.querySelectorAll(".tab").forEach((tab) => {
    tab.addEventListener("click", () => {
      document
        .querySelectorAll(".tab")
        .forEach((t) => t.classList.remove("active"));
      tab.classList.add("active");

      document.querySelectorAll(".tab-content").forEach((c) => {
        c.hidden = true;
      });
      document.getElementById(tab.dataset.tab).hidden = false;

      currentTab = tab.dataset.tab;
      renderTable(currentTab, datasets[currentTab]);
      document.getElementById("search").value = "";
    });
  });

  // Search
  document.getElementById("search").addEventListener("input", function () {
    const value = this.value.toLowerCase();
    const filtered = datasets[currentTab].filter((o) =>
      Object.values(o).some((v) => String(v).toLowerCase().includes(value))
    );
    renderTable(currentTab, filtered);
  });

  // Rating Stars
  const stars = document.querySelectorAll("#ratingStars span");
  stars.forEach((star, index) => {
    star.addEventListener("click", () => {
      stars.forEach((s) => s.classList.remove("active"));
      for (let i = 0; i <= index; i++) stars[i].classList.add("active");
    });
  });

  // Rating History
  document.getElementById("openHistory").addEventListener("click", () => {
    const historyData = [
      { date: "2025-08-01", rating: 4, notes: "Good performance" },
      { date: "2025-07-15", rating: 5, notes: "Excellent work" },
      { date: "2025-06-10", rating: 3, notes: "Needs improvement" },
    ];
    const tbody = document.getElementById("ratingHistoryBody");
    tbody.innerHTML = historyData
      .map(
        (h) => `
      <tr><td>${h.date}</td><td>${"★".repeat(h.rating)}</td><td>${
          h.notes
        }</td></tr>
    `
      )
      .join("");
    openModal(ratingHistoryModal);
  });

  document.getElementById("closeHistoryModal").addEventListener("click", () => {
    closeModal(ratingHistoryModal);
  });

  // Save Changes
  document.querySelector(".btn.save").addEventListener("click", () => {
    const notes = document.getElementById("evaluationDesc").value;
    if (notes.trim() !== "") alert("Changes saved!");
    else alert("No changes to save.");
    closeModal(officerModal);
  });
});
