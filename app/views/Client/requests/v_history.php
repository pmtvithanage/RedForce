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
            <p>View all your previous service requests and their current status</p>
        </div>

        <div class="history-content">
            <?php if (empty($previousRequests)): ?>
                <div class="empty-state">
                    <span class="material-icons">inbox</span>
                    <h3>No Requests Found</h3>
                    <p>You haven't submitted any service requests yet.</p>
                    <button class="primary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/requests'">
                        <span class="material-icons">add</span>
                        Create Request
                    </button>
                </div>
            <?php else: ?>
                <div class="requests-grid">
                    <?php foreach ($previousRequests as $request): ?>
                        <div class="request-card">
                            <div class="request-card-header">
                                <h3><?php echo htmlspecialchars($request['eventName']); ?></h3>
                                <span class="status-badge status-<?php echo strtolower($request['status']); ?>">
                                    <?php echo strtoupper($request['status']); ?>
                                </span>
                            </div>
                            
                            <div class="request-card-body">
                                <div class="detail-item">
                                    <span class="material-icons">description</span>
                                    <div>
                                        <label>Description</label>
                                        <p><?php echo htmlspecialchars($request['eventDescription']); ?></p>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="material-icons">event</span>
                                    <div>
                                        <label>Date</label>
                                        <p><?php echo $request['startDate']; ?> to <?php echo $request['endDate']; ?></p>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="material-icons">schedule</span>
                                    <div>
                                        <label>Time</label>
                                        <p><?php echo $request['startTime']; ?> - <?php echo $request['endTime']; ?></p>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="material-icons">location_on</span>
                                    <div>
                                        <label>Location</label>
                                        <p><?php echo htmlspecialchars($request['location']); ?></p>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="material-icons">group</span>
                                    <div>
                                        <label>Number of Guards</label>
                                        <p><?php echo $request['numberOfGuards']; ?> guards</p>
                                    </div>
                                </div>
                                
                                <?php if (!empty($request['comments'])): ?>
                                <div class="detail-item">
                                    <span class="material-icons">comment</span>
                                    <div>
                                        <label>Comments</label>
                                        <p><?php echo htmlspecialchars($request['comments']); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="detail-item">
                                    <span class="material-icons">calendar_today</span>
                                    <div>
                                        <label>Submitted</label>
                                        <p><?php echo $request['submittedDate']; ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="request-card-actions">
                                <form method="POST" action="<?php echo URL_ROOT; ?>/client/requestHistory" 
                                      onsubmit="return confirm('Are you sure you want to delete this request?');">
                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" name="delete_request" class="secondary-btn delete-btn">
                                        <span class="material-icons">delete</span>
                                        Delete Request
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
