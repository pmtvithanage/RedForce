<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


<!--<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/clients_style.css">-->
<style>
.stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  margin : 12px;
  margin-bottom: 18px;
}

.stat-card {
  position: relative;
  background: #fff;
  border-radius: 20px;
  box-shadow: #f5f4f4ff;
  height: 110px;
  display: flex;
  align-items: center;
  overflow: hidden;
}

.client-requests-card:hover {
  cursor: pointer;
}
.stat-pill {
  position: absolute;
  left: 12px;
  top: 12px;
  bottom: 12px;
  width: 10px;
  background: #a40000;
  border-radius: 12px;
  box-shadow: inset 0 0 0 4px #fff;
}
.pill-right {
  left: auto;
  right: 12px;
}
.stat-body {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  gap: 18px;
}
.stat-value {
  font-size: 42px;
  font-weight: 800;
}
.stat-label {
  font-size: 22px;
  color: var(--ink);
}

.search-wrap {
  position: relative;
  width: 100%;
  max-width: 560px;
  margin: 12px auto 8px;
}
.search {
  width: 100%;
  height: 38px;
  border: 2px solid #000;
  border-radius: 8px;
  background: #fff;
  padding: 0 36px 0 36px;
  outline: none;
  font-size: 15px;
}
.search:focus {
  border-color: #a40000 ;
}
.search-icon {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 14px;
  color: #999;
  pointer-events: none;
}

.card-wrapper {
    display: flex;
    justify-content: center;   /* centers horizontally */
    align-items: center;       /* optional: centers vertically */
    flex-wrap: wrap;
    gap: 20px;
}

.client-card {
    justify: center;
    width: 650px;
    padding: 20px;
    border-radius: 16px;
    background: #fff;
    border: 2px solid #a40000 ;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    font-family: Arial, sans-serif;
}

.client-title {
    margin: 0 0 10px 0;
}

.badge {
    width: 150px;
    background: #fc5050ff;
    margin-bottom: 15px;
}

.client-stats {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.stat-box {
    background: #fff;
    padding: 15px 22px;
    width: 100px;
    height: 100px;
    border-radius: 12px;
    text-align: center;
    border: 2px solid #ddd;
}

.number {
    display: block;
    font-size: 22px;
    font-weight: bold;
}

.officer-label {
    font-size: 12px;
    color: #444;
}

.view-btn {
  margin-left: auto;
    writing-mode: vertical-rl;
    padding: 12px 10px;
    background: #fff;
    border: 2px solid #a40000 ;
    border-radius: 12px;
    cursor: pointer;
}

.address {
    margin-top: 10px;
    color: #555;
    font-size: 13px;
}


.add-btn {
  position: fixed;
  bottom: 24px;
  right: 24px;

  width: 58px;
  height: 58px;

  background: #a40000;
  border: none;
  border-radius: 50%;
  box-shadow: 0 4px 10px rgba(0,0,0,0.25);

  display: flex;
  align-items: center;
  justify-content: center;

  cursor: pointer;
  transition: 0.2s;
}

.add-btn:hover {
  background: #c90000;
  transform: scale(1.07);
}

.add-btn span {
  color: #fff;
  font-size: 32px;
}

</style>




<div class="page">
  <header class="stats">
    <div class="stat-card">
      <div class="stat-pill"></div>
      <div class="stat-body">
        <div class="stat-value" id="clientsCount">0</div>
        <div class="stat-label">Clients</div>
      </div>
    </div>

    <div class="stat-card client-requests-card" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/clientRequests'">
      <?php if (isset($data['pendingRequestsCount']) && $data['pendingRequestsCount'] > 0): ?>
        <span class="notification-badge"><?php echo $data['pendingRequestsCount']; ?></span>
      <?php endif; ?>
      <div class="stat-pill pill-right"></div>
      <div class="stat-body">
        <div class="stat-label">Client Requests</div>
      </div>
    </div>
  </header>

  <div class="search-wrap">
    <input id="searchInput" class="search" type="text" placeholder="Search" />
    <span class="search-icon"><span class="material-icons">search</span></span>
  </div>
</div>

      <!-- Client cards-->
<div class="card-wrapper">
  <div class="client-card">
      <h2 class="client-title">People's Bank PLC</h2>

      

      <div class="client-stats">
        <div class="badge">PEOPLE'S</div>

          <div class="stat-box">
              <span class="number">142</span>
              <span class="officer-label">Premise <br> officers</span>
          </div>

          <div class="stat-box">
              <span class="number">13</span>
              <span class="officer-label">Supervisors</span>
          </div>

          <div class="stat-box">
              <span class="number">3</span>
              <span class="officer-label">Care-Takers</span>
          </div>

          <button class="view-btn">View</button>
      </div>

      <p class="address">No.75 Sir Chittampalam A. Gardiner Mawatha, Colombo 2</p>
  </div>
</div>

<div>
  <button class="add-btn">
    <span class="material-icons">add</span>
  </button>
</div>



</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>


<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
