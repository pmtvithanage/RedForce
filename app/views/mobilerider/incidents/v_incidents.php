<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>

<style>
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 4px solid #007bff;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .stat-card.total {
        border-left-color: #007bff;
    }

    .stat-card.pending {
        border-left-color: #ffc107;
    }

    .stat-card.resolved {
        border-left-color: #28a745;
    }

    .stat-card.in-progress {
        border-left-color: #17a2b8;
    }

    .stat-card-icon {
        font-size: 36px;
        margin-bottom: 10px;
        opacity: 0.8;
    }

    .stat-card.total .stat-card-icon {
        color: #007bff;
    }

    .stat-card.pending .stat-card-icon {
        color: #ffc107;
    }

    .stat-card.resolved .stat-card-icon {
        color: #28a745;
    }

    .stat-card.in-progress .stat-card-icon {
        color: #17a2b8;
    }

    .stat-card-title {
        font-size: 14px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .stat-card-value {
        font-size: 32px;
        font-weight: bold;
        color: #333;
    }

    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .stats-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stats-container">
    <div class="stat-card total">
        <div class="stat-card-icon">📊</div>
        <div class="stat-card-title">Total Incidents</div>
        <div class="stat-card-value"><?php echo isset($data['total_incidents']) ? $data['total_incidents'] : '0'; ?></div>
    </div>

    <div class="stat-card pending">
        <div class="stat-card-icon">⏳</div>
        <div class="stat-card-title">Pending</div>
        <div class="stat-card-value"><?php echo isset($data['pending_incidents']) ? $data['pending_incidents'] : '0'; ?></div>
    </div>

    <div class="stat-card in-progress">
        <div class="stat-card-icon">🔄</div>
        <div class="stat-card-title">In Progress</div>
        <div class="stat-card-value"><?php echo isset($data['inprogress_incidents']) ? $data['inprogress_incidents'] : '0'; ?></div>
    </div>

    <div class="stat-card resolved">
        <div class="stat-card-icon">✅</div>
        <div class="stat-card-title">Resolved</div>
        <div class="stat-card-value"><?php echo isset($data['resolved_incidents']) ? $data['resolved_incidents'] : '0'; ?></div>
    </div>
</div>

    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>                       