<?php
// Quick fix for undefined variables
if (!isset($data)) {
    $data = [];
}
if (!isset($data['title'])) {
    $data['title'] = 'Request History - RedForce';
}

$previousRequests = $data['previousRequests'] ?? [];
if (!is_array($previousRequests)) {
    $previousRequests = [];
}
?>

<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/requests/history_style.css">

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="main-content">
    <!-- Back Button -->
    <button class="tertiary-btn back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/requests'">
        <span class="material-icons">arrow_back</span>
        Back
    </button>

    <div class="history-container">
        <div class="history-header">
            <h2>Request History</h2>
        </div>

        <?php if (empty($data['requests'])): ?>
            <div class="empty-state">
                <span class="material-icons">inbox</span>
                <p>No previous requests found.</p>
            </div>
        <?php else: ?>
            <div class="requests-grid">
                <?php foreach ($data['requests'] as $request): ?>
                    <div class="request-card">
                        <div class="request-header">
                            <h3><?php echo htmlspecialchars($request->event_name); ?></h3>
                            <span class="status-badge status-<?php echo strtolower($request->status); ?>">
                                <?php echo strtoupper($request->status); ?>
                            </span>
                        </div>
                        
                        <div class="request-body">
                            <div class="detail-row">
                                <span class="material-icons">description</span>
                                <span><?php echo htmlspecialchars($request->event_description); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="material-icons">date_range</span>
                                <span><?php echo date('M d, Y', strtotime($request->start_date)); ?> - <?php echo date('M d, Y', strtotime($request->end_date)); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="material-icons">schedule</span>
                                <span><?php echo date('h:i A', strtotime($request->start_time)); ?> - <?php echo date('h:i A', strtotime($request->end_time)); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="material-icons">location_on</span>
                                <span><?php echo htmlspecialchars($request->location); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="material-icons">security</span>
                                <span><?php echo $request->number_of_guards; ?> Guards</span>
                            </div>
                            <?php if (!empty($request->comments)): ?>
                            <div class="detail-row">
                                <span class="material-icons">comment</span>
                                <span><?php echo htmlspecialchars($request->comments); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="request-footer">
                            <span class="submitted-date">
                                <span class="material-icons">event</span>
                                Submitted: <?php echo date('M d, Y', strtotime($request->created_at)); ?>
                            </span>
                            <form method="POST" action="<?php echo URL_ROOT; ?>/client/deleteRequest/<?php echo $request->id; ?>" 
                                  onsubmit="return confirm('Are you sure you want to delete this request?');">
                                <button type="submit" class="delete-btn">
                                    <span class="material-icons">delete</span>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
