<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
  <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/scheduling_style.css">


    <!-- Content will be loaded here -->
  <div class="container">

    <!-- Header -->
    <header class="header">
      <div class="requests-box">5 Requests</div>
    </header>

    <!-- Client / Site search -->
    <div class="search-section">
      <div class="search-box">
        <label>Client</label>
        <input type="text" placeholder="Search">
        <button>View</button>
      </div>
      <div class="search-box">
        <label>Site</label>
        <input type="text" placeholder="Search">
        <button>View</button>
      </div>
    </div>

    <hr>

    <!-- Scrollable Duty Points container -->
    <div id="dutyPointsContainer" class="scroll-container"></div>

    <hr>

    <div class="footer">
      <button class="new-duty-btn">Create New Duty Point</button>
    </div>
  </div>

  <!-- Template for Duty Point -->
  <template id="dutyPointTemplate">
  <div class="duty-point">
    <h3 class="duty-title">Duty Point</h3>

    <div class="supervisor">
      <label>Supervisor</label>
      <input type="text" value="Supervisor Name">
      <span class="badge day">Day</span>
      <button class="replace-btn">Replace</button>
    </div>

    <div class="supervisor">
      <label>Supervisor</label>
      <input type="text" value="Supervisor Name">
      <span class="badge night">Night</span>
      <button class="replace-btn">Replace</button>
    </div>

    <table class="officer-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Officer ID</th>
          <th>Officer</th>
          <th>Rank</th>
          <th>Duty Time</th>
          <th>Assignment</th>
          <th>Rating</th>
          <th colspan="2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Officers injected -->
      </tbody>
    </table>

    <button class="add-officer">Add Officer</button>
  </div>
</template>

<hr>

<!-- Mobile Rider Section -->
<div class="mobile-rider-section">
  <div class="mobile-rider-search">
    <label for="riderSearch">Search Rider</label>
    <input type="text" id="riderSearch" placeholder="Enter rider name...">
    <button id="searchRiderBtn">Search</button>
    <button id="addRiderBtn" class="add-rider">Add Rider</button>
  </div>

  <div id="mobileRidersContainer"></div>
</div>

<!-- Template for Mobile Rider -->
<template id="mobileRiderTemplate">
  <div class="mobile-rider">
    <h3 class="mobile-title">Mobile Rider</h3>

    <div class="rider">
      <label>Rider</label>
      <input type="text" value="Kasun Samanta">
      <button class="replace-btn">Replace</button>
    </div>

    <table class="site-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Site ID</th>
          <th>Location</th>
        </tr>
      </thead>
      <tbody>
        <!-- Sites injected -->
      </tbody>
    </table>

    <button class="add-site">Add Site</button>
  </div>
</template>

<!-- Officers Table Popup -->
<div id="officersPopup" class="popup-overlay" style="display: none;">
  <div class="popup-content">
    <div class="popup-header">
      <h3>Available Officers</h3>
      <button class="close-popup">&times;</button>
    </div>
    <div class="popup-body">
      <table class="officers-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Officer ID</th>
            <th>Officer</th>
            <th>Rank</th>
            <th>Status</th>
            <th>Assignment</th>
            <th>Rating</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>PF231</td>
            <td> Nuwan Perera</td>
            <td>OIC</td>
            <td>On Duty</td>
            <td>People's Bank PLC</td>
            <td>1403</td>
            <td><button class="select-officer">Select</button></td>
          </tr>
          <tr>
            <td>2</td>
            <td>PF416</td>
            <td> Kasun Silva</td>
            <td>OIC</td>
            <td>On Leave</td>
            <td>Cargills PLC</td>
            <td>1399</td>
            <td><button class="select-officer">Select</button></td>
          </tr>
          <tr>
            <td>3</td>
            <td>PF664</td>
            <td> Dilan Jayasuriya</td>
            <td>OIC</td>
            <td>On Duty</td>
            <td>Aitken Spence PLC</td>
            <td>1382</td>
            <td><button class="select-officer">Select</button></td>
          </tr>
          <tr>
            <td>4</td>
            <td>PF220</td>
            <td> Chamika Bandara</td>
            <td>Level 4</td>
            <td>On Duty</td>
            <td>Sri Lanka Telecom</td>
            <td>1380</td>
            <td><button class="select-officer">Select</button></td>
          </tr>
          <tr>
            <td>5</td>
            <td>PF100</td>
            <td> Suranga Kumara</td>
            <td>OIC</td>
            <td>On Duty</td>
            <td>Petroleum Corporation</td>
            <td>1376</td>
            <td><button class="select-officer">Select</button></td>
          </tr>
          <tr>
            <td>6</td>
            <td>PF230</td>
            <td> Tharindu Wickramasinghe</td>
            <td>Level 3</td>
            <td>On Break</td>
            <td>People's Bank PLC</td>
            <td>1373</td>
            <td><button class="select-officer">Select</button></td>
          </tr>
          <tr>
            <td>7</td>
            <td>DE141</td>
            <td> Malinda Lacash</td>
            <td>Level 4</td>
            <td>On Leave</td>
            <td>Mausloka Locatial</td>
            <td>1370</td>
            <td><button class="select-officer">Select</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- New Duty Point Popup -->
<div id="newDutyPointPopup" class="popup-overlay" style="display: none;">
  <div class="popup-content new-duty-popup">
    <div class="popup-header">
      <h3>Create New Duty Point</h3>
      <button class="close-popup">&times;</button>
    </div>
    <div class="popup-body">
      <form id="newDutyPointForm" class="duty-point-form">
        <div class="form-group">
          <label for="dutyPointName">Duty Point Name</label>
          <input type="text" id="dutyPointName" placeholder="Enter duty point name" required>
        </div>

        <div class="supervisors-section">
          <h4>Supervisors</h4>
          <div class="form-group">
            <label>Day Supervisor</label>
            <div class="supervisor-input-group">
              <input type="text" id="daySupervisor" placeholder="Select day supervisor" readonly>
              <button type="button" class="select-supervisor-btn" data-duty="day">Select</button>
            </div>
          </div>
          <div class="form-group">
            <label>Night Supervisor</label>
            <div class="supervisor-input-group">
              <input type="text" id="nightSupervisor" placeholder="Select night supervisor" readonly>
              <button type="button" class="select-supervisor-btn" data-duty="night">Select</button>
            </div>
          </div>
        </div>

        <div class="officers-section">
          <h4>Officers</h4>
          <div id="newOfficersList" class="officers-list">
            <!-- Officers will be added here dynamically -->
          </div>
          <button type="button" class="add-officer-btn">Add Officer</button>
        </div>

        <div class="form-actions">
          <button type="button" class="cancel-btn">Cancel</button>
          <button type="submit" class="create-btn">Create Duty Point</button>
        </div>
      </form>
    </div>
  </div>
</div>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/admin/scheduling.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>