<?php
// Mock officer data - replace with actual database query
$officerId = $_GET['id'] ?? 'RF001';
$officers = [
    'RF001' => ['name' => 'John Silva', 'rank' => 'OIC', 'site' => 'Colombo Main Branch', 'rating' => 4.2],
    'RF002' => ['name' => 'Sarah Perera', 'rank' => 'SSO', 'site' => 'Kurunegala Branch', 'rating' => 4.8],
    'RF003' => ['name' => 'Michael Fernando', 'rank' => 'JSO', 'site' => 'Nuwara Eliya Branch', 'rating' => 3.9],
    'RF004' => ['name' => 'Lisa Jayawardene', 'rank' => 'LSO', 'site' => 'Galle Branch', 'rating' => 4.5],
    'RF005' => ['name' => 'David Rathnayake', 'rank' => 'OIC', 'site' => 'Kandy Branch', 'rating' => 4.1],
    'RF006' => ['name' => 'Amanda Wickramasinghe', 'rank' => 'SSO', 'site' => 'Colombo Main Branch', 'rating' => 4.7],
    'RF007' => ['name' => 'Pradeep Kumara', 'rank' => 'JSO', 'site' => 'Kurunegala Branch', 'rating' => 4.3],
    'RF008' => ['name' => 'Nisha Mendis', 'rank' => 'LSO', 'site' => 'Galle Branch', 'rating' => 4.0],
    'RF009' => ['name' => 'Ruwan Dissanayake', 'rank' => 'JSO', 'site' => 'Nuwara Eliya Branch', 'rating' => 3.8],
    'RF010' => ['name' => 'Chamila Rajapakse', 'rank' => 'SSO', 'site' => 'Kandy Branch', 'rating' => 4.6],
    'RF011' => ['name' => 'Kasun Weerasinghe', 'rank' => 'LSO', 'site' => 'Colombo Main Branch', 'rating' => 4.4],
    'RF012' => ['name' => 'Tharaka Gunasekera', 'rank' => 'JSO', 'site' => 'Kurunegala Branch', 'rating' => 4.2]
];

$officer = isset($officers[$officerId]) ? $officers[$officerId] : null;
if ($officer) {
    $officer['id'] = $officerId;
}

// Handle rating submission
$submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rating'])) {
    // Process rating here (save to database)
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    $submitted = true;
}
?>

<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/officers/rate_style.css">

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="main-content">
    <!-- Back Button -->
    <button class="tertiary-btn back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/officers'">
        <span class="material-icons">arrow_back</span>
        Back to Officers
    </button>

    <?php if ($submitted): ?>
    <div class="alert-message success">
        <span class="material-icons">check_circle</span>
        <div>
            <h3>Rating Submitted Successfully!</h3>
            <p>Thank you for your feedback. Your rating has been recorded.</p>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($officer): ?>
    <div class="rating-container">
        <div class="officer-profile-card">
            <div class="officer-avatar-large">
                <?php echo strtoupper(substr($officer['name'], 0, 1)); ?>
                <div class="status-indicator"></div>
            </div>
            <div class="officer-details">
                <h2><?php echo htmlspecialchars($officer['name']); ?></h2>
                <div class="detail-row">
                    <span class="material-icons">badge</span>
                    <span>Officer ID: <?php echo htmlspecialchars($officer['id']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="material-icons">military_tech</span>
                    <span>Rank: <?php echo htmlspecialchars($officer['rank']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="material-icons">location_on</span>
                    <span>Location: <?php echo htmlspecialchars($officer['site']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="material-icons">star</span>
                    <span>Current Rating: <?php echo number_format($officer['rating'], 1); ?> / 5.0</span>
                </div>
            </div>
        </div>

        <div class="rating-form-card">
            <h3>Evaluate Officer Performance</h3>
            <p class="form-description">Please provide your honest feedback about this officer's performance and professionalism.</p>

            <form method="POST" action="">
                <input type="hidden" name="officer_id" value="<?php echo htmlspecialchars($officer['id']); ?>">
                
                <div class="form-group">
                    <label>Rating <span class="required">*</span></label>
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5" class="star" title="Excellent">★</label>
                        
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4" class="star" title="Very Good">★</label>
                        
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3" class="star" title="Good">★</label>
                        
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2" class="star" title="Fair">★</label>
                        
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1" class="star" title="Poor">★</label>
                    </div>
                    <p class="rating-label" id="ratingLabel">Click to rate</p>
                </div>
                
                <div class="form-group">
                    <label for="description">Comments <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="5" required placeholder="Share your experience with this officer's service..."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/officers'">Cancel</button>
                    <button type="submit" name="submit_rating" class="primary-btn">
                        <span class="material-icons">send</span>
                        Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="error-container">
        <span class="material-icons">error_outline</span>
        <h2>Officer Not Found</h2>
        <p>The officer you're trying to rate could not be found.</p>
        <button class="primary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/officers'">
            Back to Officers
        </button>
    </div>
    <?php endif; ?>
</div>

<script src="<?php echo URL_ROOT; ?>/js/client/officers/rate.js"></script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
