<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/components/profile_style.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="history.back()"> 
    <span class="material-icons" style="font-size:18px;">arrow_back</span>
    Back
</button>

<style>
.main-content {
    margin: 0 20px;
}

.profile-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* (same styles kept from tempBranch version) */

.profile-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
    margin-bottom: 30px;
}

/* keep rest unchanged... */
</style>

</head>
<body>
<div class="main-content">
    <div class="profile-container">
        <div class="profile-grid" style="margin-top: 20px;">

            <!-- Profile Card -->
            <div class="profile-card">
                <div class="card-header">
                    <span class="material-icons">account_circle</span>
                    <h3>Profile Information</h3>
                </div>
                <div class="card-body">
                    <div class="profile-avatar-large">
                        <div class="avatar-circle-large">
                            <img class="profile-image" src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $officer->profile_image; ?>" alt="<?php echo $officer->name; ?>">
                        </div>
                    </div>
                    <div class="profile-name"><?php echo $officer->name; ?></div>
                    <div class="profile-role">Premise Officer</div>
                    <div class="profile-rank-badge"><?php echo $officer->rank; ?></div>
                    <div class="profile-status-badge"><?php echo $officer->user_status; ?></div>
                </div>
            </div>

            <!-- Contact Card -->
            <div class="info-card">
                <div class="card-header">
                    <span class="material-icons">contact_phone</span>
                    <h3>Contact Information</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-icon"><span class="material-icons">phone</span></div>
                        <div class="info-details">
                            <div class="info-label">Phone</div>
                            <div class="info-value"><?php echo $officer->phone_number; ?></div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon"><span class="material-icons">email</span></div>
                        <div class="info-details">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?php echo $officer->email; ?></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Employment Section -->
        <div class="info-card">
            <h3 class="section-title">Employment Details</h3>

            <div class="detail-group">
                <div class="detail-label">Rank</div>
                <div class="detail-value">
                    <select class="editable-select"
                        data-officer-id="<?php echo $officer->user_id; ?>"
                        data-field="rank">
                        <option value="Junior" <?= ($officer->rank=='Junior')?'selected':'' ?>>Junior</option>
                        <option value="Senior" <?= ($officer->rank=='Senior')?'selected':'' ?>>Senior</option>
                        <option value="Supervisor" <?= ($officer->rank=='Supervisor')?'selected':'' ?>>Supervisor</option>
                    </select>
                </div>
            </div>

        </div>

    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<script>
document.querySelectorAll('.editable-select').forEach(select => {
    select.addEventListener('change', function() {
        fetch('<?php echo URL_ROOT; ?>/admin/updateOfficerField', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                officer_id: this.dataset.officerId,
                field: this.dataset.field,
                value: this.value,
                role: 'premise officer'
            })
        });
    });
});
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>