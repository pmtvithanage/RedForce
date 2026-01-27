<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<!-- <span class="material-icons">face</span> -->


    <button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="history.back()"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #a40000;
            --primary-dark: #7a0000;
            --primary-light: #d84f4f;
            --light-gray: #f5f5f5;
            --medium-gray: #e0e0e0;
            --dark-gray: #333;
            --text-color: #444;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --border-radius: 8px;
        }


        .container {
            margin: 20px;   
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--medium-gray);
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            color: var(--dark-gray);
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .profile-card {
            display: flex;
            flex-direction: column;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        @media (min-width: 768px) {
            .profile-card {
                flex-direction: row;
            }
        }

        .profile-header {
            background: linear-gradient(to right, #a40000, #fe8e8eff);
            color: var(--white);
            padding: 30px;
            text-align: center;
            flex: 1;
        }

        @media (min-width: 768px) {
            .profile-header {
                flex: 0 0 300px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
        }

        .profile-image {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }

        .profile-name {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .profile-rank {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .profile-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-active {
            background-color: rgba(76, 175, 80, 0.2);
            color: #2e7d32;
        }

        .status-on-leave {
            background-color: rgba(255, 152, 0, 0.2);
            color: #ef6c00;
        }

        .profile-contact {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .contact-item i {
            width: 20px;
            color: var(--primary-light);
        }

        .profile-details {
            flex: 2;
            padding: 30px;
        }

        .section-title {
            color: var(--primary-color);
            font-size: 1.4rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .detail-group {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-label i {
            color: var(--primary-color);
            width: 20px;
        }

        .detail-value {
            padding: 10px 15px;
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary-color);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-junior {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .badge-senior {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .badge-supervisor {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--medium-gray);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-secondary {
            background-color: var(--light-gray);
            color: var(--dark-gray);
        }

        .btn-secondary:hover {
            background-color: var(--medium-gray);
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: var(--dark-gray);
            font-size: 0.9rem;
            border-top: 1px solid var(--medium-gray);
            margin-top: 30px;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
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
    <div class="container">

        <main class="profile-card">
            <div class="profile-header">
                <img class="profile-image" src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $officer->profile_image; ?>" alt="<?php echo $officer->name; ?>" >
                <h2 class="profile-name"><?php echo $officer->name; ?></h2>
                <div class="profile-rank"><?php echo $officer->rank; ?></div>
                <div class="profile-status status-active"><?php echo $officer->user_status; ?></div>
                
                <div class="profile-contact">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span><?php echo $officer->phone_number; ?></span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span><?php echo $officer->email; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="profile-details">
                <h3 class="section-title">Personal Information</h3>
                <div class="details-grid">
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-id-card"></i>
                            Officer ID
                        </div>
                        <div class="detail-value"><?php echo $officer->caretakerID; ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-birthday-cake"></i>
                            Date of Birth
                        </div>
                        <div class="detail-value"><?php echo $officer->date_of_birth; ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-address-card"></i>
                            NIC Number
                        </div>
                        <div class="detail-value"><?php echo $officer->NIC; ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-venus-mars"></i>
                            Gender
                        </div>
                        <div class="detail-value"><?php echo $officer->gender; ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Address
                        </div>
                        <div class="detail-value"><?php echo $officer->address; ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-map"></i>
                            District
                        </div>
                        <div class="detail-value"><?php echo $officer->district; ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-city"></i>
                            City
                        </div>
                        <div class="detail-value"><?php echo $officer->city; ?></div>
                    </div>
                </div>
                
                <h3 class="section-title">Employment Details</h3>
                <div class="details-grid">
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-calendar-alt"></i>
                            Hire Date
                        </div>
                        <div class="detail-value"><?php echo $officer->hire_date; ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-user-tie"></i>
                            Rank
                        </div>
                        <div class="detail-value">
                            <select id="rank" class="editable-select" data-officer-id="<?php echo $officer->user_id; ?>" data-field="rank" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: white; font-weight: 600;">
                                <option value="Junior" <?php echo ($officer->rank == 'Junior') ? 'selected' : ''; ?>>Junior</option>
                                <option value="Senior" <?php echo ($officer->rank == 'Senior') ? 'selected' : ''; ?>>Senior</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-clock"></i>
                            Shift Pattern
                        </div>
                        <div class="detail-value"><?php echo $officer->shift_pattern; ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-calendar-check"></i>
                            Employment Status
                        </div>
                        <div class="detail-value">
                            <select id="employment_status" class="editable-select" data-officer-id="<?php echo $officer->user_id; ?>" data-field="employment_status" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: white; font-weight: 600;">
                                <option value="Active" <?php echo ($officer->user_status == 'Active') ? 'selected' : ''; ?>>Active</option>
                                <option value="On Leave" <?php echo ($officer->user_status == 'On Leave') ? 'selected' : ''; ?>>On Leave</option>
                                <option value="Suspended" <?php echo ($officer->user_status == 'Suspended') ? 'selected' : ''; ?>>Suspended</option>
                                <option value="Inactive" <?php echo ($officer->user_status == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-file-alt"></i>
                            Application ID
                        </div>
                        <div class="detail-value"><?php echo $officer->application_id; ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label">
                            <i class="fas fa-calendar-plus"></i>
                            Last Updated
                        </div>
                        <div class="detail-value"><?php echo $officer->user_account_updated; ?></div>
                    </div>
                </div>
                
                <div class="actions">
                    <button class="btn btn-secondary">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                    <button class="btn btn-primary">
                        View Calander
                    </button>
                </div>
            </div>
        </main>
        
        <footer class="footer">
           
        </footer>
    </div>

    
</body>
</html>
    </main>
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