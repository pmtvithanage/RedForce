// Demo data; replace with API data if needed
const clients = [
	{
		name: "People’s Bank PLC",
		logoText: "PEOPLE'S",
		address: "No.75 Sir Chittampalam A. Gardiner Mawatha, Colombo 2",
		stats: { officers: 142, supervisors: 13, caretakers: 3 }
	},
	{
		name: "Sri Lanka Telecom",
		logoText: "SLTMOBITE",
		address: "Colombo (Corporate HQ)",
		stats: { officers: 95, supervisors: 9, caretakers: 5 }
	},
	{
		name: "Mas Holdings PLC",
		logoText: "MAS",
		address: "Colombo",
		stats: { officers: 77, supervisors: 6, caretakers: 4 }
	},
	...Array.from({ length: 15 }, (_, i) => ({
		name: `Sample Client ${i + 1}`,
		logoText: "LOGO",
		address: "City, Country",
		stats: { officers: 20 + i, supervisors: 5 + (i % 7), caretakers: 2 + (i % 5) }
	}))
];

const listEl = document.getElementById("clientsList");
const searchEl = document.getElementById("searchInput");
const clientsCountEl = document.getElementById("clientsCount");
const requestsCountEl = document.getElementById("requestsCount");
const modalEl = document.getElementById("clientModal");
const modalNameEl = document.getElementById("modalClientName");
const sitesListEl = document.getElementById("sitesList");
const sitesCountEl = document.getElementById("sitesCount");
const siteSearchEl = document.getElementById("siteSearch");
const closeBtn = document.getElementById("closeModal");
const paymentToggle = document.getElementById("paymentToggle");
const paymentPanel = document.getElementById("paymentPanel");

// Site modal refs
const siteModalEl = document.getElementById('siteModal');
const staffSearchEl = document.getElementById('staffSearch');
const staffBodyEl = document.getElementById('staffBody');
const closeSiteBtn = document.getElementById('closeSiteModal');
const tabButtons = () => Array.from(document.querySelectorAll('#siteModal .tab'));

const REQUESTS_COUNT = 3;

function renderClients(items){
	listEl.innerHTML = "";
	items.forEach(c => {
		const card = document.createElement("article");
		card.className = "card";
		card.innerHTML = `
			<div class="card-inner">
				<div>
					<h3 class="card-title">${c.name}</h3>
					<div class="company">
						<div class="logo">${c.logoText}</div>
						<div class="meta">
							<div class="pill">
								<div class="num">${c.stats.officers}</div>
								<div class="sub">Premise<br/>officers</div>
							</div>
							<div class="pill">
								<div class="num">${c.stats.supervisors}</div>
								<div class="sub">Supervisors</div>
							</div>
							<div class="pill">
								<div class="num">${c.stats.caretakers}</div>
								<div class="sub">Care-Takers</div>
							</div>
						</div>
					</div>
					<div class="address">${c.address}</div>
				</div>
				<div class="view-wrap">
					<button class="view-btn" aria-label="View ${c.name}">View</button>
				</div>
			</div>
		`;
		listEl.appendChild(card);

		const btn = card.querySelector('.view-btn');
		btn.addEventListener('click', () => openClientModal(c));
	});
	clientsCountEl.textContent = items.length.toString();
	requestsCountEl.textContent = REQUESTS_COUNT.toString();
}

function filterClients(){
	const q = searchEl.value.trim().toLowerCase();
	if(!q){ renderClients(clients); return; }
	const filtered = clients.filter(c =>
		c.name.toLowerCase().includes(q) ||
		c.address.toLowerCase().includes(q)
	);
	renderClients(filtered);
}

renderClients(clients);
searchEl.addEventListener("input", filterClients);

// ---------- Modal logic ----------
function mockSitesFor(client){
	return Array.from({ length: 9 }, (_, i) => ({
		code: `Z${22342 + i}`,
		address: "75 Sir Chittampalam A. Gardiner Mawatha",
		phone: "0112 481 481",
		stats: { officers: 10 + i, supervisors: 1 + (i % 3), caretakers: i % 2 }
	}));
}

function renderSites(sites){
	sitesListEl.innerHTML = "";
	sites.forEach(s => {
		const row = document.createElement('div');
		row.className = 'site-card';
		row.innerHTML = `
			<div>
				<div class="site-title">${s.code}</div>
				<div class="site-meta">
					Address: ${s.address}<br/>
					Phone No: ${s.phone}
				</div>
			</div>
			<div class="site-stats">
				<div class="site-pill"><div class="num">${s.stats.officers}</div><div class="sub">Premise<br/>officers</div></div>
				<div class="site-pill"><div class="num">${s.stats.supervisors}</div><div class="sub">Supervisors</div></div>
				<div class="site-pill"><div class="num">${s.stats.caretakers}</div><div class="sub">Care-Takers</div></div>
			</div>
		`;
		sitesListEl.appendChild(row);

		row.addEventListener('click', () => openSiteModal(s));
	});
	sitesCountEl.textContent = sites.length.toString();
}

let currentSites = [];
function openClientModal(client){
	modalEl.classList.add('open');
	modalEl.setAttribute('aria-hidden', 'false');
	modalNameEl.textContent = client.name;
	currentSites = mockSitesFor(client);
	renderSites(currentSites);
	siteSearchEl.value = '';
}

function closeClientModal(){
	modalEl.classList.remove('open');
	modalEl.setAttribute('aria-hidden', 'true');
}

siteSearchEl.addEventListener('input', () => {
	const q = siteSearchEl.value.trim().toLowerCase();
	if(!q){ renderSites(currentSites); return; }
	const filtered = currentSites.filter(s => s.code.toLowerCase().includes(q) || s.address.toLowerCase().includes(q));
	renderSites(filtered);
});

paymentToggle.addEventListener('click', () => {
	paymentPanel.classList.toggle('open');
	const chev = paymentToggle.querySelector('.chev');
	chev.textContent = paymentPanel.classList.contains('open') ? '▴' : '▾';
});

closeBtn.addEventListener('click', closeClientModal);
modalEl.addEventListener('click', (e) => { if(e.target.hasAttribute('data-close')) closeClientModal(); });
document.addEventListener('keydown', (e) => { if(e.key === 'Escape' && modalEl.classList.contains('open')) closeClientModal(); });

// ---------- Site modal ----------
let currentRole = 'officers';
let currentStaff = { officers: [], supervisors: [], caretakers: [] };

function mockStaffForSite(site){
	const make = (n) => Array.from({ length: n }, (_, i) => ({
		id: `PF${200 + i}`,
		name: 'Nuwan Perera',
		rank: `Level ${1 + (i % 3)}`,
		status: 'On Duty',
		rating: 800 + Math.floor(Math.random() * 600)
	}));
	return {
		officers: make(10),
		supervisors: make(4),
		caretakers: make(3)
	};
}

function openSiteModal(site){
	currentRole = 'officers';
	setActiveTab(currentRole);
	currentStaff = mockStaffForSite(site);
	staffSearchEl.value = '';
	renderStaff(currentRole, currentStaff[currentRole]);

	siteModalEl.classList.add('open');
	siteModalEl.setAttribute('aria-hidden', 'false');
}

function closeSiteModal(){
	siteModalEl.classList.remove('open');
	siteModalEl.setAttribute('aria-hidden', 'true');
}

function setActiveTab(role){
	tabButtons().forEach(btn => {
		const isActive = btn.getAttribute('data-tab') === role;
		btn.classList.toggle('active', isActive);
	});
}

function renderStaff(role, list){
	staffBodyEl.innerHTML = '';
	list.forEach(person => {
		const tr = document.createElement('tr');
		tr.innerHTML = `
			<td>${person.id}</td>
			<td>👤\u00A0\u00A0${person.name}</td>
			<td>${person.rank}</td>
			<td><span class="badge">${person.status}</span></td>
			<td>${person.rating}</td>
		`;
		staffBodyEl.appendChild(tr);
	});
}

function filterStaff(){
	const q = staffSearchEl.value.trim().toLowerCase();
	const base = currentStaff[currentRole];
	if(!q){ renderStaff(currentRole, base); return; }
	const filtered = base.filter(p => p.id.toLowerCase().includes(q) || p.name.toLowerCase().includes(q) || p.rank.toLowerCase().includes(q));
	renderStaff(currentRole, filtered);
}

staffSearchEl.addEventListener('input', filterStaff);
closeSiteBtn.addEventListener('click', closeSiteModal);
siteModalEl.addEventListener('click', (e) => { if(e.target.hasAttribute('data-close')) closeSiteModal(); });
document.addEventListener('keydown', (e) => { if(e.key === 'Escape' && siteModalEl.classList.contains('open')) closeSiteModal(); });

tabButtons().forEach(btn => {
	btn.addEventListener('click', () => {
		currentRole = btn.getAttribute('data-tab');
		setActiveTab(currentRole);
		filterStaff();
	});
});
