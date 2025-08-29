// ===== Sample Datasets =====
const datasets = {
  officers: [
    { id: "PF231", name: "Nuwan Perera", rank: "OIC", status: "On Duty", assignment: "People’s Bank PLC", rating: 1403 },
    { id: "PF416", name: "Kasun Silva", rank: "OIC", status: "On Leave", assignment: "Cargills PLC", rating: 1399 },
    { id: "PF664", name: "Dilan Jayasuriya", rank: "OIC", status: "On Duty", assignment: "Aitken Spence PLC", rating: 1382 },
    { id: "PF220", name: "Chamika Bandara", rank: "Level 4", status: "On Duty", assignment: "Sri Lanka Telecom", rating: 1380 },
    { id: "PF100", name: "Suranga Kumara", rank: "OIC", status: "On Duty", assignment: "Petroleum Corporation", rating: 1376 },
    { id: "PF230", name: "Tharindu Wickramasinghe", rank: "Level 3", status: "On Break", assignment: "People’s Bank PLC", rating: 1373 },
    { id: "PF141", name: "Malinda Herath", rank: "Level 4", status: "On Leave", assignment: "Nawaloka Hospital", rating: 1370 },
    { id: "PF348", name: "Ruwan Gamage", rank: "OIC", status: "On Duty", assignment: "Durdans Hospital", rating: 1363 },
    { id: "PF552", name: "Janith Peiris", rank: "Level 3", status: "On Duty", assignment: "Commercial Bank HQ", rating: 1358 },
    { id: "PF687", name: "Amal Rathnayake", rank: "OIC", status: "On Break", assignment: "Sampath Bank PLC", rating: 1347 }
  ],
  mobileRiders: [
    { id: "MR101", name: "Sanjaya Peris", vehicle: "Bike", status: "On Duty", rating: 1201 },
    { id: "MR102", name: "Ramesh Nuwan", vehicle: "Scooter", status: "On Leave", rating: 1195 },
    { id: "MR103", name: "Pradeep Silva", vehicle: "Bike", status: "On Break", rating: 1187 },
    { id: "MR104", name: "Thushara Bandara", vehicle: "Motorbike", status: "On Duty", rating: 1179 },
    { id: "MR105", name: "Isuru Fernando", vehicle: "Three Wheeler", status: "On Duty", rating: 1165 },
    { id: "MR106", name: "Pasindu Jayawardena", vehicle: "Scooter", status: "On Leave", rating: 1154 },
    { id: "MR107", name: "Lakshan Dissanayake", vehicle: "Bike", status: "On Duty", rating: 1149 },
    { id: "MR108", name: "Viraj Perera", vehicle: "Motorbike", status: "On Break", rating: 1142 }
  ],
  careTakers: [
    { id: "CT501", name: "Lahiru Weerasinghe", shift: "Morning", status: "On Duty" },
    { id: "CT502", name: "Nadeesha Priyani", shift: "Evening", status: "On Leave" },
    { id: "CT503", name: "Manoj Rajapaksha", shift: "Night", status: "On Duty" },
    { id: "CT504", name: "Ravindu Gunasekara", shift: "Morning", status: "On Duty" },
    { id: "CT505", name: "Dilini Wickramasinghe", shift: "Evening", status: "On Leave" },
    { id: "CT506", name: "Chamari Jayasinghe", shift: "Night", status: "On Break" },
    { id: "CT507", name: "Kavindu Senarath", shift: "Morning", status: "On Duty" },
    { id: "CT508", name: "Anjali Ratnayake", shift: "Evening", status: "On Duty" }
  ]
};

// ===== Render Tables =====
function renderTable(tab, data) {
  const map = { officers: "officerTable", mobileRiders: "riderTable", careTakers: "caretakerTable" };
  const tbody = document.getElementById(map[tab]);
  if (!tbody) return;
  tbody.innerHTML = "";

  data.forEach((row, i) => {
    let statusClass = row.status === "On Duty" ? "on-duty" :
                      row.status === "On Leave" ? "on-leave" :
                      row.status === "On Break" ? "on-break" : "";

    if (tab === "officers") {
      tbody.insertAdjacentHTML("beforeend", `
        <tr>
          <td>${i + 1}</td><td>${row.id}</td><td>${row.name}</td>
          <td>${row.rank}</td>
          <td><span class="status ${statusClass}">${row.status}</span></td>
          <td>${row.assignment}</td><td>${row.rating}</td>
        </tr>`);
    }
    if (tab === "mobileRiders") {
      tbody.insertAdjacentHTML("beforeend", `
        <tr>
          <td>${i + 1}</td><td>${row.id}</td><td>${row.name}</td>
          <td>${row.vehicle}</td>
          <td><span class="status ${statusClass}">${row.status}</span></td>
          <td>${row.rating}</td>
        </tr>`);
    }
    if (tab === "careTakers") {
      tbody.insertAdjacentHTML("beforeend", `
        <tr>
          <td>${i + 1}</td><td>${row.id}</td><td>${row.name}</td>
          <td>${row.shift}</td>
          <td><span class="status ${statusClass}">${row.status}</span></td>
        </tr>`);
    }
  });
}

// ===== Modal Helpers =====
const modal = document.getElementById("officerModal");
const closeBtn = document.getElementById("closeModal");

function openModal() { modal.style.display = "flex"; modal.classList.add("show"); }
function closeModal() { modal.style.display = "none"; modal.classList.remove("show"); }

function fillModal(record) {
  document.getElementById("officerName").textContent = record.name || "-";
  document.getElementById("officerId").textContent = record.id || "-";
  document.getElementById("officerRank").textContent =
    record.rank || record.vehicle || record.shift || "-";
  document.getElementById("officerLocation").textContent = record.assignment || "-";
  document.getElementById("officerRating").textContent = record.rating || "-";
}

// ===== Row Click -> Modal =====
document.addEventListener("click", (e) => {
  const tr = e.target.closest("tbody tr");
  if (!tr) return;

  const wrapper = tr.closest(".tab-content");
  if (!wrapper || wrapper.hidden) return;

  let tabKey = wrapper.id; // officers | mobileRiders | careTakers
  const recId = tr.cells[1]?.innerText.trim();
  const record = (datasets[tabKey] || []).find(r => r.id === recId);
  if (!record) return;

  fillModal(record);
  openModal();
});

// ===== Close Modal =====
if (closeBtn) closeBtn.addEventListener("click", closeModal);
modal.addEventListener("click", (e) => { if (e.target === modal) closeModal(); });
document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeModal(); });

// ===== Tabs =====
let currentTab = "officers";
renderTable(currentTab, datasets[currentTab]);

document.querySelectorAll(".tab").forEach(tab => {
  tab.addEventListener("click", () => {
    document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
    tab.classList.add("active");

    document.querySelectorAll(".tab-content").forEach(c => { c.hidden = true; });
    document.getElementById(tab.dataset.tab).hidden = false;

    currentTab = tab.dataset.tab;
    renderTable(currentTab, datasets[currentTab]);
    document.getElementById("search").value = "";
  });
});

// ===== Search =====
document.getElementById("search").addEventListener("input", function () {
  const value = this.value.toLowerCase();
  const filtered = datasets[currentTab].filter(o =>
    Object.values(o).some(v => String(v).toLowerCase().includes(value))
  );
  renderTable(currentTab, filtered);
});

// ===== Rating Stars =====
const stars = document.querySelectorAll("#ratingStars span");
stars.forEach((star, index) => {
  star.addEventListener("click", () => {
    stars.forEach(s => s.classList.remove("active"));
    for (let i = 0; i <= index; i++) stars[i].classList.add("active");
  });
});

// ===== Rating History =====
document.getElementById("openHistory").addEventListener("click", () => {
  const historyData = [
    { date: "2025-08-01", rating: 4, notes: "Good performance" },
    { date: "2025-07-15", rating: 5, notes: "Excellent work" },
    { date: "2025-06-10", rating: 3, notes: "Needs improvement" }
  ];
  const tbody = document.getElementById("ratingHistoryBody");
  tbody.innerHTML = historyData.map(h => `
    <tr><td>${h.date}</td><td>${"★".repeat(h.rating)}</td><td>${h.notes}</td></tr>
  `).join("");
  document.getElementById("ratingHistoryModal").classList.add("show");
});
document.getElementById("closeHistoryModal").addEventListener("click", () => {
  document.getElementById("ratingHistoryModal").classList.remove("show");
});

// ===== Update Rank =====
const rankModal = document.getElementById("rankUpdateModal");
const rankSelect = document.getElementById("rankSelect");
let currentOfficerId = null;

document.querySelector(".btn.rank").addEventListener("click", () => {
  currentOfficerId = document.getElementById("officerId").textContent;
  rankModal.classList.add("show");
});

document.getElementById("confirmRankUpdate").addEventListener("click", () => {
  const newRank = rankSelect.value;
  if (!newRank) return;
  let record = datasets.officers.find(o => o.id === currentOfficerId);
  if (record) {
    record.rank = newRank;
    document.getElementById("officerRank").textContent = newRank;
    renderTable("officers", datasets.officers);
    alert(`Rank updated to ${newRank}`);
  }
  rankModal.classList.remove("show");
});
document.getElementById("cancelRankUpdate").addEventListener("click", () => {
  rankModal.classList.remove("show");
});
rankModal.addEventListener("click", (e) => { if (e.target === rankModal) rankModal.classList.remove("show"); });

// ===== Save Changes =====
document.querySelector(".btn.save").addEventListener("click", () => {
  const notes = document.getElementById("evaluationDesc").value;
  if (notes.trim() !== "") alert("Changes saved!");
  else alert("No changes to save.");
  closeModal();
});