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

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 24px;
        margin-bottom: 30px;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .card-header h3 {
        flex: 1;
        margin: 0;
        margin-left: 12px;
        color: #333;
        font-size: 1.2rem;
    }

    .card-header .material-icons {
        color: #a40000;
        font-size: 28px;
    }

    .profile-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 24px;
    }

    .info-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 24px;
    }

    .card-body {
        color: #555;
    }

    .profile-avatar-large {
        position: relative;
        width: fit-content;
        margin: 0 auto 20px;
    }

    .avatar-circle-large {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        border: 4px solid #a40000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin: 0 auto;
    }

    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .profile-name {
        text-align: center;
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin: 15px 0 8px;
    }

    .profile-role {
        text-align: center;
        font-size: 0.95rem;
        color: #a40000;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .profile-rank-badge {
        text-align: center;
        display: inline-block;
        width: 100%;
        background: #fff3e0;
        color: #ef6c00;
        padding: 8px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .profile-status-badge {
        text-align: center;
        display: inline-block;
        width: 100%;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 8px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .info-row {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        align-items: flex-start;
    }

    .info-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f5f5;
        border-radius: 50%;
    }

    .info-icon .material-icons {
        color: #a40000;
        font-size: 20px;
    }

    .info-details {
        flex: 1;
    }

    .info-label {
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 4px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 1rem;
        color: #333;
        font-weight: 500;
    }

    .section-title {
        color: #a40000;
        font-size: 1.2rem;
        font-weight: 600;
        margin: 24px 0 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #d84f4f;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .detail-group {
        margin-bottom: 15px;
    }

    .detail-label {
        font-weight: 600;
        color: #666;
        margin-bottom: 8px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-label .material-icons {
        color: #a40000;
        font-size: 18px;
    }

    .detail-value {
        padding: 12px;
        background-color: #f5f5f5;
        border-radius: 6px;
        border-left: 4px solid #a40000;
        font-size: 0.95rem;
        color: #333;
    }

    .detail-value select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
        font-weight: 600;
        color: #333;
        cursor: pointer;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        grid-column: 1 / -1;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-primary {
        background-color: #a40000;
        color: white;
    }

    .btn-primary:hover {
        background-color: #7a0000;
    }

    .btn-secondary {
        background-color: #f0f0f0;
        color: #333;
        border: 1px solid #ddd;
    }

    .btn-secondary:hover {
        background-color: #e0e0e0;
    }

    @media (max-width: 768px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .actions {
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }
</style>
</head>
<body>
    <div class="main-content">
        <div class="profile-container">
            <div class="profile-grid" style="margin-top: 20px;">
                <!-- Profile Information Card -->
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
                        <div class="profile-role">Caretaker</div>
                        <div class="profile-rank-badge"><?php echo $officer->rank; ?></div>
                        <div class="profile-status-badge"><?php echo $officer->user_status; ?></div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="info-card">
                    <div class="card-header">
                        <span class="material-icons">contact_phone</span>
                        <h3>Contact Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-icon">
                                <span class="material-icons">phone</span>
                            </div>
                            <div class="info-details">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value"><?php echo $officer->phone_number; ?></div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <span class="material-icons">email</span>
                            </div>
                            <div class="info-details">
                                <div class="info-label">Email Address</div>
                                <div class="info-value"><?php echo $officer->email; ?></div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <span class="material-icons">badge</span>
                            </div>
                            <div class="info-details">
                                <div class="info-label">Caretaker ID</div>
                                <div class="info-value"><?php echo $officer->caretakerID; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Card -->
                <div class="info-card">
                    <div class="card-header">
                        <span class="material-icons">person</span>
                        <h3>Personal Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-icon">
                                <span class="material-icons">cake</span>
                            </div>
                            <div class="info-details">
                                <div class="info-label">Date of Birth</div>
                                <div class="info-value"><?php echo $officer->date_of_birth; ?></div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <span class="material-icons">assignment_ind</span>
                            </div>
                            <div class="info-details">
                                <div class="info-label">NIC Number</div>
                                <div class="info-value"><?php echo $officer->NIC; ?></div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <span class="material-icons">wc</span>
                            </div>
                            <div class="info-details">
                                <div class="info-label">Gender</div>
                                <div class="info-value"><?php echo $officer->gender; ?></div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <span class="material-icons">location_on</span>
                            </div>
                            <div class="info-details">
                                <div class="info-label">Address</div>
                                <div class="info-value"><?php echo $officer->address; ?></div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <span class="material-icons">map</span>
                            </div>
                            <div class="info-details">
                                <div class="info-label">District</div>
                                <div class="info-value"><?php echo $officer->district; ?></div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <span class="material-icons">location_city</span>
                            </div>
                            <div class="info-details">
                                <div class="info-label">City</div>
                                <div class="info-value"><?php echo $officer->city; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Details Section -->
            <div class="info-card" style="margin-bottom: 30px;">
                <h3 class="section-title">Employment Details</h3>
                <div class="details-grid">
                    <div class="detail-group">
                        <div class="detail-label">
                            <span class="material-icons">calendar_today</span>
                            Hire Date
                        </div>
                        <div class="detail-value"><?php echo $officer->hire_date; ?></div>
                    </div>

                    <div class="detail-group">
                        <div class="detail-label">
                            <span class="material-icons">work</span>
                            Rank
                        </div>
                        <div class="detail-value">
                            <select id="rank" class="editable-select" data-officer-id="<?php echo $officer->user_id; ?>" data-field="rank">
                                <option value="Junior" <?php echo ($officer->rank == 'Junior') ? 'selected' : ''; ?>>Junior</option>
                                <option value="Senior" <?php echo ($officer->rank == 'Senior') ? 'selected' : ''; ?>>Senior</option>
                            </select>
                        </div>
                    </div>

                    <div class="detail-group">
                        <div class="detail-label">
                            <span class="material-icons">schedule</span>
                            Shift Pattern
                        </div>
                        <div class="detail-value"><?php echo $officer->shift_pattern; ?></div>
                    </div>

                    <div class="detail-group">
                        <div class="detail-label">
                            <span class="material-icons">task_alt</span>
                            Employment Status
                        </div>
                        <div class="detail-value">
                            <select id="employment_status" class="editable-select" data-officer-id="<?php echo $officer->user_id; ?>" data-field="employment_status">
                                <option value="Active" <?php echo ($officer->user_status == 'Active') ? 'selected' : ''; ?>>Active</option>
                                <option value="On Leave" <?php echo ($officer->user_status == 'On Leave') ? 'selected' : ''; ?>>On Leave</option>
                                <option value="Suspended" <?php echo ($officer->user_status == 'Suspended') ? 'selected' : ''; ?>>Suspended</option>
                                <option value="Inactive" <?php echo ($officer->user_status == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="detail-group">
                        <div class="detail-label">
                            <span class="material-icons">document_scanner</span>
                            Application ID
                        </div>
                        <div class="detail-value"><?php echo $officer->application_id; ?></div>
                    </div>

                    <div class="detail-group">
                        <div class="detail-label">
                            <span class="material-icons">update</span>
                            Last Updated
                        </div>
                        <div class="detail-value"><?php echo $officer->user_account_updated; ?></div>
                    </div>
                </div>

                <div class="actions">
                    <button class="btn btn-secondary" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/editOfficer/<?php echo $officer->user_id; ?>'">
                        <span class="material-icons">edit</span> Edit Profile
                    </button>
                    <button class="btn btn-primary" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/viewOfficerCalendar/<?php echo $officer->user_id; ?>'">
                        <span class="material-icons">calendar_month</span> View Calendar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
    <script>
        // Auto-save functionality for editable selects
        document.querySelectorAll('.editable-select').forEach(select => {
            select.addEventListener('change', function() {
                const officerId = this.dataset.officerId;
                const field = this.dataset.field;
                const value = this.value;
                const originalBg = this.style.backgroundColor;
                
                console.log('Updating:', {officerId, field, value, role: 'caretaker'});
                
                // Show loading state
                this.style.backgroundColor = '#fff3cd';
                this.disabled = true;
                
                // Send update to server
                fetch('<?php echo URL_ROOT; ?>/admin/updateOfficerField', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        officer_id: officerId,
                        field: field,
                        value: value,
                        role: 'caretaker'
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Show success state
                        this.style.backgroundColor = '#d4edda';
                        setTimeout(() => {
                            this.style.backgroundColor = originalBg;
                        }, 1500);
                    } else {
                        console.error('Update failed:', data);
                        alert('Error updating field: ' + (data.message || 'Unknown error'));
                        this.style.backgroundColor = '#f8d7da';
                        setTimeout(() => {
                            this.style.backgroundColor = originalBg;
                        }, 1500);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Error updating field: ' + error.message);
                    this.style.backgroundColor = '#f8d7da';
                    setTimeout(() => {
                        this.style.backgroundColor = originalBg;
                    }, 1500);
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    </script>
</body>
</html>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
    <script>
        // Auto-save functionality for editable selects
        document.querySelectorAll('.editable-select').forEach(select => {
            select.addEventListener('change', function() {
                const officerId = this.dataset.officerId;
                const field = this.dataset.field;
                const value = this.value;
                const originalBg = this.style.backgroundColor;
                
                // Show loading state
                this.style.backgroundColor = '#fff3cd';
                this.disabled = true;
                
                // Send update to server
                fetch('<?php echo URL_ROOT; ?>/admin/updateOfficerField', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        officer_id: officerId,
                        field: field,
                        value: value,
                        role: 'caretaker'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success state
                        this.style.backgroundColor = '#d4edda';
                        setTimeout(() => {
                            this.style.backgroundColor = originalBg;
                        }, 1500);
                    } else {
                        alert('Error updating field: ' + (data.message || 'Unknown error'));
                        this.style.backgroundColor = '#f8d7da';
                        setTimeout(() => {
                            this.style.backgroundColor = originalBg;
                        }, 1500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating field');
                    this.style.backgroundColor = '#f8d7da';
                    setTimeout(() => {
                        this.style.backgroundColor = originalBg;
                    }, 1500);
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    </script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>