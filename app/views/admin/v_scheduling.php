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
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/admin/scheduling.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>