<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<button class="tertiary-btn" style="display:flex; width:100px; margin: 20px; align-items:center;" onclick="history.back()">
    <span class="material-icons" style="font-size:18px;">arrow_back</span>
    Back
</button>

<style>
    .main-content {
        margin: 0 20px;
    }

    .ratings-container {
        max-width: 1200px;
        margin: 0 auto 30px;
    }

    .summary-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 18px;
        margin-bottom: 18px;
        border-left: 5px solid #a40000;
    }

    .summary-title {
        margin: 0 0 8px;
        color: #222;
        font-size: 22px;
        font-weight: 700;
    }

    .summary-meta {
        color: #666;
        font-size: 14px;
        margin: 0;
    }

    .ratings-table-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .ratings-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ratings-table thead th {
        background: #f9e9e9;
        color: #3b1c1c;
        padding: 12px;
        text-align: left;
        font-size: 14px;
    }

    .ratings-table td {
        padding: 12px;
        border-top: 1px solid #eee;
        vertical-align: top;
        font-size: 14px;
        color: #333;
    }

    .ratings-table tbody tr:hover {
        background: #fafafa;
    }

    .score-badge {
        display: inline-block;
        min-width: 52px;
        text-align: center;
        padding: 6px 10px;
        border-radius: 14px;
        background: #fff3e0;
        color: #e65100;
        font-weight: 700;
    }

    .role-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .role-client {
        background: #e8f5e9;
        color: #1b5e20;
    }

    .role-supervisor {
        background: #e3f2fd;
        color: #0d47a1;
    }

    .description-text {
        white-space: pre-wrap;
        line-height: 1.4;
    }

    .empty-ratings {
        padding: 40px 18px;
        text-align: center;
        color: #666;
    }

    .empty-ratings .material-icons {
        font-size: 44px;
        color: #bbb;
        margin-bottom: 10px;
    }

    @media (max-width: 900px) {
        .ratings-table thead {
            display: none;
        }

        .ratings-table,
        .ratings-table tbody,
        .ratings-table tr,
        .ratings-table td {
            display: block;
            width: 100%;
        }

        .ratings-table tr {
            border-top: 1px solid #eee;
            padding: 10px 0;
        }

        .ratings-table td {
            border-top: none;
            padding: 6px 12px;
        }
    }
</style>

<div class="main-content">
    <div class="ratings-container">
        <div class="summary-card">
            <h2 class="summary-title">Ratings - <?php echo htmlspecialchars($data['officer']->name ?? 'Officer'); ?></h2>
            <p class="summary-meta">
                Officer ID: <?php echo htmlspecialchars($data['officer']->officerID ?? 'N/A'); ?>
                | Total Entries: <?php echo count($data['ratings'] ?? []); ?>
            </p>
        </div>

        <div class="ratings-table-card">
            <?php if (!empty($data['ratings'])): ?>
                <table class="ratings-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Rating</th>
                            <th>Reviewer</th>
                            <th>Role</th>
                            <th>Site</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['ratings'] as $rating): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rating->rating_date); ?></td>
                                <td><span class="score-badge"><?php echo (int)$rating->rating_value; ?>/5</span></td>
                                <td><?php echo htmlspecialchars($rating->reviewer_name ?? 'N/A'); ?></td>
                                <td>
                                    <span class="role-badge <?php echo ($rating->reviewer_role === 'client') ? 'role-client' : 'role-supervisor'; ?>">
                                        <?php echo htmlspecialchars($rating->reviewer_role); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($rating->site_name ?? 'N/A'); ?></td>
                                <td class="description-text"><?php echo htmlspecialchars($rating->description ?: '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-ratings">
                    <span class="material-icons">rate_review</span>
                    <p>No ratings available for this officer yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
