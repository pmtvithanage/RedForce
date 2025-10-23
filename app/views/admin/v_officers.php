<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/officers_style.css">

<div class="container">
  <!-- Tabs -->
  <div class="tabs">
    <button class="tab active" data-tab="officers">Officers</button>
    <button class="tab" data-tab="mobileRiders">Mobile Riders</button>
    <button class="tab" data-tab="careTakers">Care-Takers</button>
  </div>

  <!-- Table container -->
  <div class="table-container">
    <input type="text" id="search" placeholder="🔍 Search">

    <!-- Officers -->
    <div class="table-wrapper tab-content active" id="officers">
      <table>
        <colgroup>
          <col style="width:6%">
          <col style="width:14%">
          <col style="width:26%">
          <col style="width:12%">
          <col style="width:12%">
          <col style="width:20%">
          <col style="width:10%">
        </colgroup>
        <thead>
          <tr>
            <th>#</th>
            <th>Officer ID</th>
            <th>Officer</th>
            <th>Rank</th>
            <th>Status</th>
            <th>Assignment</th>
            <th>Rating</th>
          </tr>
        </thead>
        <tbody id="officerTable"><!-- JS fills rows --></tbody>
      </table>
    </div>

    <!-- Mobile Riders -->
    <div class="table-wrapper tab-content" id="mobileRiders" hidden>
      <table>
        <colgroup>
          <col style="width:6%">
          <col style="width:18%">
          <col style="width:30%">
          <col style="width:16%">
          <col style="width:20%">
          <col style="width:10%">
        </colgroup>
        <thead>
          <tr>
            <th>#</th>
            <th>Rider ID</th>
            <th>Name</th>
            <th>Vehicle</th>
            <th>Status</th>
            <th>Rating</th>
          </tr>
        </thead>
        <tbody id="riderTable"><!-- JS fills rows --></tbody>
      </table>
    </div>

    <!-- Care-Takers -->
    <div class="table-wrapper tab-content" id="careTakers" hidden>
      <table>
        <colgroup>
          <col style="width:6%">
          <col style="width:20%">
          <col style="width:34%">
          <col style="width:20%">
          <col style="width:20%">
        </colgroup>
        <thead>
          <tr>
            <th>#</th>
            <th>Care-Taker ID</th>
            <th>Name</th>
            <th>Shift</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="caretakerTable"><!-- JS fills rows --></tbody>
      </table>
    </div>
  </div>

  <!-- Footer Stats -->
  <div class="stats">
    <div>
      <h2 id="activeCount">1434</h2>
      <p>Active Officers</p>
    </div>
    <div>
      <h2 id="retiredCount">947</h2>
      <p>Retired Officers</p>
    </div>
  </div>

  <!-- Bottom Buttons -->
  <div class="actions">
    <button class="btn open" id="openRecruitmentBtn">Open Recruitment</button>
    <button class="btn add" id="addOfficersBtn">+ Add Officers</button>
    <button class="btn apps">4 Applications</button>
  </div>
</div>

<!-- Extra closing tags cleanup -->
</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<!-- Recruitment Modal -->
<div id="recruitmentModal" class="recruitment-modal">
  <div class="overlay" data-close></div>
  <div class="modal-content">
    <h3>Recruitment</h3>
    
    <!-- Recruitment Type Dropdown -->
    <div class="recruitment-section">
      <h4>Recruitment Type</h4>
      <select id="recruitmentType" class="recruitment-dropdown">
        <option value="">Select Recruitment Type</option>
        <option value="premise-officer">Premise Officer</option>
        <option value="care-taker">Care Taker</option>
        <option value="mobile-rider">Mobile Rider</option>
      </select>
    </div>

    <!-- Description Section -->
    <div class="recruitment-section">
      <h4>Description</h4>
      <textarea class="description-textarea" placeholder="Enter job description..."></textarea>
    </div>

    <!-- Qualifications Section -->
    <div class="recruitment-section">
      <h4>Qualifications</h4>
      <div class="qualifications-input">
        <input type="text" id="qualificationInput" placeholder="Add qualification...">
        <button class="btn add-qualification" id="addQualificationBtn">+ Add</button>
      </div>
      <ul class="qualifications-list" id="qualificationsList">
        <!-- Qualifications will be added here dynamically -->
      </ul>
    </div>

    <!-- Publisher Section -->
    <div class="recruitment-section">
      <h4>Publisher</h4>
      <div class="publisher-info">
        <input type="text" id="publisherInput" placeholder="Enter publisher name...">
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="modal-actions">
      <button class="btn publish" id="publishRecruitmentBtn">Publish</button>
      <button class="btn close" id="closeRecruitmentModal">Close</button>
    </div>
  </div>
</div>

<!-- Applications Modal -->
<div id="applicationsModal" class="applications-modal">
  <div class="overlay" data-close></div>
  <div class="modal-content">
    <h3>Applications</h3>
    <div class="applications-list">
      <!-- Application 1 -->
      <div class="application-item">
        <div class="applicant-info">
          <h4>I.W.Karunarathne</h4>
          <p>NIC: 19937901880</p>
          <p>Mobile No: 0775621231</p>
        </div>
        <div class="application-actions">
          <div class="pdf-badge">PDF</div>
          <div class="cv-file">cv.PDF</div>
          <div class="application-date">2025/08/10 10:28</div>
          <button class="btn add-small">Add</button>
        </div>
      </div>
      
      <!-- Application 2 -->
      <div class="application-item">
        <div class="applicant-info">
          <h4>G.H.Perera</h4>
          <p>NIC: 20013901880</p>
          <p>Mobile No: 076621341</p>
        </div>
        <div class="application-actions">
          <div class="pdf-badge">PDF</div>
          <div class="cv-file">cv.PDF</div>
          <div class="application-date">2025/08/10 11:42</div>
          <button class="btn add-small">Add</button>
        </div>
      </div>
      
      <!-- Application 3 -->
      <div class="application-item">
        <div class="applicant-info">
          <h4>K.D.Jayakody</h4>
          <p>NIC: 19957361550</p>
          <p>Mobile No: 0775621231</p>
        </div>
        <div class="application-actions">
          <div class="pdf-badge">PDF</div>
          <div class="cv-file">cv.PDF</div>
          <div class="application-date">2025/08/10 12:04</div>
          <button class="btn add-small">Add</button>
        </div>
      </div>
    </div>
    <div class="modal-actions">
      <button class="btn close" id="closeApplicationsModal">Close</button>
    </div>
  </div>
</div>

<!-- Officer Details Modal -->
<div id="officerModal" class="officer-modal">
  <div class="overlay" data-close></div>
  <div class="modal-content">
    <div class="modal-body">
      <!-- Left Section: Officer Info -->
      <div class="officer-card">
        <div class="avatar">
          <span class="status-dot"></span>
          <div class="avatar-icon">👤</div>
          <button class="edit-btn">Edit</button>
        </div>
        <p><strong>Name:</strong> <span id="officerName"></span></p>
        <p><strong>Officer ID:</strong> <span id="officerId"></span></p>
        <p><strong>Rank:</strong> <span id="officerRank"></span></p>
        <p><strong>Location:</strong> <span id="officerLocation"></span></p>
        <p><strong>Rating:</strong> <span id="officerRating"></span></p>
      </div>

      <!-- Right Section: Evaluation -->
      <div class="evaluate-card">
        <h3>Evaluate Officer</h3>
        <textarea id="evaluationDesc" placeholder="Description"></textarea>

        <!-- Rating stars -->
        <div class="stars" id="ratingStars">
          <span data-value="1">★</span>
          <span data-value="2">★</span>
          <span data-value="3">★</span>
          <span data-value="4">★</span>
          <span data-value="5">★</span>
        </div>

        <!-- Action Buttons -->
        <div class="modal-actions">
          <button class="btn history" id="openHistory">View Rating History</button>
          <button class="btn rank">Update Rank</button>
          <button class="btn save">Save Changes</button>
          <button class="btn close" id="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Rating History Modal -->
<div id="ratingHistoryModal" class="rating-history-modal">
  <div class="overlay" data-close></div>
  <div class="modal-content">
    <h3>Rating History</h3>
    <table class="history-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Rating</th>
          <th>Notes</th>
        </tr>
      </thead>
      <tbody id="ratingHistoryBody"><!-- JS fills rows --></tbody>
    </table>
    <div class="modal-actions">
      <button class="btn close" id="closeHistoryModal">Close</button>
    </div>
  </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/admin/officers.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>