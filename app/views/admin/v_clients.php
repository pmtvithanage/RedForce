<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/clients_style.css">

<div class="page">
  <header class="stats">
    <div class="stat-card">
      <div class="stat-pill"></div>
      <div class="stat-body">
        <div class="stat-value" id="clientsCount">0</div>
        <div class="stat-label">Clients</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-pill pill-right"></div>
      <div class="stat-body">
        <div class="stat-value" id="requestsCount">0</div>
        <div class="stat-label">Client Requests</div>
      </div>
    </div>
  </header>

  <div class="search-wrap">
    <input id="searchInput" class="search" type="text" placeholder="Search" />
    <span class="search-icon">🔍</span>
  </div>

  <main class="list-wrap">
    <div id="clientsList" class="cards"></div>
  </main>
</div>

<!-- Client Modal -->
<div id="clientModal" class="client-modal" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="overlay" data-close></div>
  <div class="modal-card" role="document">
    <div class="modal-body">
      <div class="modal-left">
        <h2 id="modalClientName" class="modal-title"></h2>
        <div class="modal-logo"></div>
        <div class="messages">
          <div class="messages-label">Messages</div>
          <div class="messages-box" contenteditable="true" aria-label="Messages"></div>
        </div>
      </div>
      <div class="modal-right">
        <div class="sites-header">
          <div>Sites - <span id="sitesCount">0</span></div>
          <div class="search-wrap small">
            <input id="siteSearch" class="search" type="text" placeholder="Search" />
            <span class="search-icon">🔍</span>
          </div>
        </div>
        <div id="sitesList" class="sites-list"></div>
        <button class="accordion" id="paymentToggle">
          Payment History
          <span class="chev">▾</span>
        </button>
        <div id="paymentPanel" class="panel">No payments yet.</div>
        <div class="modal-actions">
          <button id="closeModal" class="close-btn">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Site Modal -->
<div id="siteModal" class="site-modal" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="overlay" data-close></div>
  <div class="site-modal-card" role="document">
    <div class="tabs">
      <button class="tab active" data-tab="officers">Premise Officers</button>
      <button class="tab" data-tab="supervisors">Supervisors</button>
      <button class="tab" data-tab="caretakers">Care-Takers</button>
    </div>
    <div class="search-wrap mid">
      <input id="staffSearch" class="search" type="text" placeholder="Search" />
      <span class="search-icon">🔍</span>
    </div>
    <div class="table-wrap">
      <table class="staff-table">
        <thead>
          <tr>
            <th>Officer ID</th>
            <th>Officer</th>
            <th>Rank</th>
            <th>Status</th>
            <th>Rating</th>
          </tr>
        </thead>
        <tbody id="staffBody"></tbody>
      </table>
    </div>
    <div class="modal-actions">
      <button id="closeSiteModal" class="close-btn">Close</button>
    </div>
  </div>
</div>

<!-- Extra closing tags in original seem mismatched — fix structure -->
</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/admin/clients.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>