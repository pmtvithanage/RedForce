<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


<!--<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/clients_style.css">-->
<style>
  /* ===== Global Enhancements ===== */
  :root {
    --primary: #a40000;
    --primary-light: #c90000;
    --bg-soft: #fafafa;
    --card-bg: #ffffff;
    --ink: #222;
    --shadow-soft: 0 4px 14px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 6px 18px rgba(0, 0, 0, 0.12);
  }


  /* ===== Stats Section ===== */
  .stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin: 18px;
  }

  .stat-card {
    background: var(--card-bg);
    border-radius: 18px;
    box-shadow: var(--shadow-soft);
    height: 120px;
    display: flex;
    align-items: center;
    padding: 0 18px;
    position: relative;
    overflow: hidden;
    transition: 0.25s;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
  }

  .stat-body {
    display: flex;
    justify-content: space-between;
    /* pushes content to edges */
    align-items: center;
    width: 100%;
  }

  .stat-value {
    margin-left: auto;
    /* forces value to the far right */
    margin-right: 30px;
    font-size: 42px;
    font-weight: 800;
    color: var(--primary);
  }

  .stat-label {
    margin-left: 30px;
    font-size: 20px;
    color: #333;
  }

  .stat-label span.material-icons {
    vertical-align: middle;
    font-size: 50px;
    margin-right: 10px;
  }

  .client-requests-card {
    cursor: pointer;
  }

  /* ===== Search Bar ===== */
  .search-wrap {
    position: relative;
    width: 100%;
    max-width: 560px;
    margin: 12px auto 8px;
  }

  .search {
    width: 100%;
    height: 38px;
    border: 1px solid #ccccccff;
    border-radius: 8px;
    background: #fff;
    padding: 0 36px 0 36px;
    outline: none;
    font-size: 15px;
  }

  .search:focus {
    border-color: #a40000;
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

  /* ===== Clients Listing ===== */
  .card-wrapper {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 28px;
    margin-top: 20px;
  }

  .client-card {
    width: 670px;
    background: var(--card-bg);
    border-radius: 18px;
    border: 1px solid #ddd;
    box-shadow: var(--shadow-soft);
    padding: 22px;
    transition: 0.28s ease-in-out;
  }

  .client-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-hover);
    border-color: var(--primary);
  }

  /* Client Title */
  .client-title {
    margin-bottom: 10px;
    font-size: 22px;
    font-weight: 700;
    color: var(--ink);
  }

  /* Badge */
  .badge img {
    width: 160px;

  }

  /* Stats Panel inside client card */
  .client-stats {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 18px;
  }

  .stat-box {
    background: #fff;
    width: 100px;
    height: 100px;
    border-radius: 12px;
    text-align: center;
    border: 1px solid #eee;
    transition: 0.25s;
  }

  .stat-box:hover {
    transform: scale(1.07);
  }

  .number {
    display: block;
    margin-top: 22px;
    font-size: 24px;
    font-weight: 700;
    color: var(--primary);
  }

  .officer-label {
    font-size: 12px;
    color: #444;
  }

  /* View Button */
  .view-btn {
    margin-left: auto;
    writing-mode: vertical-rl;
    padding: 14px 12px;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.25s;
  }

  .view-btn:hover {
    background: var(--primary-light);
    transform: scale(1.1);
  }

  /* Address */
  .address {
    margin-top: 8px;
    font-size: 13px;
    color: #666;
  }

  /* ===== Floating Add Button ===== */
  .add-btn {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 64px;
    height: 64px;
    background: var(--primary);
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-hover);
    transition: 0.28s;
    cursor: pointer;
  }

  .add-btn:hover {
    background: var(--primary-light);
    transform: scale(1.12);
  }

  .add-btn span {
    color: #fff;
    font-size: 34px;
  }
</style>




<div class="page">
  <header class="stats">
    <div class="stat-card">
      <div class="stat-pill"></div>
      <div class="stat-body">
        <div class="stat-label"><span class="material-icons">group</span>Clients</div>
        <div class="stat-value" id="clientsCount"><?php echo $stats->total; ?></div>
      </div>
    </div>

    <div class="stat-card client-requests-card" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/clientRequests'">
      <div class="stat-pill pill-right"></div>
      <div class="stat-body">
        <div class="stat-label"><span class="material-icons">pending_actions</span>Client Requests</div>
        <div class="stat-value"><?php echo isset($data['pendingRequestsCount']) ? (int)$data['pendingRequestsCount'] : 0; ?></div>
      </div>
    </div>
  </header>

  <div class="search-wrap">
    <input id="searchInput" class="search" type="text" placeholder="Search" />
    <span class="search-icon"><span class="material-icons">search</span></span>
  </div>
</div>

<!-- Client cards-->
<?php foreach ($data['clients'] as $clients) : ?>
  <div class="card-wrapper">
    <div class="client-card">
      <div class="client-title"><?php echo $clients->name ?></div>

      <div class="client-stats">
        <div class="badge">
          <img src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo $clients->profile_image; ?>" alt="Client Logo">
        </div>

        <div class="stat-box">
          <span class="number"><?php echo $clients->officers_count ?? 0; ?></span>
          <span class="officer-label">Premise <br> officers</span>
        </div>

        <div class="stat-box">
          <span class="number"><?php echo $clients->supervisors_count ?? 0; ?></span>
          <span class="officer-label">Supervisors</span>
        </div>

        <div class="stat-box">
          <span class="number"><?php echo $clients->caretakers_count ?? 0; ?></span>
          <span class="officer-label">Care-Takers</span>
        </div>

        <button class=" primary-btn view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/clientprofile/<?php echo $clients->id ?>'">View</button>
      </div>

      <p class="address"><?php echo $clients->email ?></p>
      <p class="address"><?php echo $clients->phone_number ?></p>
    </div>
  </div>
<?php endforeach; ?>

<div>
  <button class="add-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/addclients'">
    <span class="material-icons">add</span>
  </button>
</div>



</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script>
  // Search functionality
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clientCards = document.querySelectorAll('.client-card');
    const clientsCount = document.getElementById('clientsCount');
    const totalClients = parseInt(clientsCount.textContent);

    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase().trim();
      let visibleCount = 0;

      clientCards.forEach(function(card) {
        const clientName = card.querySelector('.client-title').textContent.toLowerCase();
        const addresses = card.querySelectorAll('.address');
        const email = addresses[0] ? addresses[0].textContent.toLowerCase() : '';
        const phone = addresses[1] ? addresses[1].textContent.toLowerCase() : '';

        // Search in name, email, and phone
        const isMatch = clientName.includes(searchTerm) ||
          email.includes(searchTerm) ||
          phone.includes(searchTerm);

        if (isMatch) {
          card.parentElement.style.display = 'flex';
          visibleCount++;
        } else {
          card.parentElement.style.display = 'none';
        }
      });

      // Update count display
      if (searchTerm === '') {
        clientsCount.textContent = totalClients;
      } else {
        clientsCount.textContent = visibleCount;
      }
    });
  });
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>