<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
  <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/settings_style.css">


    <!-- Content will be loaded here -->
     <div class="container">
        <!-- Current Admins Section -->
        <div class="section current-admins">
            <h2>Current Admins</h2>
            <div class="admins-grid">
                <div class="admin-card">
                    <div class="admin-photo">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face" alt="Mr.T.N.Kaldera">
                    </div>
                    <h3>Mr.T.N.Kaldera</h3>
                    <button class="view-btn">View</button>
                </div>
                
                <div class="admin-card">
                    <div class="admin-photo">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face" alt="Mr.K.K.Adhikari">
                    </div>
                    <h3>Mr.K.K.Adhikari</h3>
                    <button class="view-btn">View</button>
                </div>
                
                <div class="admin-card">
                    <div class="admin-photo">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop&crop=face" alt="Mr.S.H.Kamal">
                    </div>
                    <h3>Mr.S.H.Kamal</h3>
                    <button class="view-btn">View</button>
                </div>
                
                <div class="admin-card">
                    <div class="admin-photo">
                        <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=150&h=150&fit=crop&crop=face" alt="Mr.N.P.Perera">
                    </div>
                    <h3>Mr.N.P.Perera</h3>
                    <button class="view-btn">View</button>
                </div>
                
                <div class="admin-card">
                    <div class="admin-photo">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face" alt="Mr.G.K.Malani">
                    </div>
                    <h3>Mr.G.K.Malani</h3>
                    <button class="view-btn">View</button>
                </div>
                
                <div class="admin-card">
                    <div class="admin-photo">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face" alt="Mr.H.K.Samantha">
                    </div>
                    <h3>Mr.H.K.Samantha</h3>
                    <button class="view-btn">View</button>
                </div>
            </div>
        </div>

        <!-- Create Admin Section -->
        <div class="section create-admin">
            <h2>Create Admin</h2>
            <form id="createAdminForm">
                <div class="form-content">
                    <div class="photo-upload">
                        <div class="upload-area" id="uploadArea">
                            <span>Upload Profile Photo</span>
                            <input type="file" id="profilePhoto" accept="image/*" hidden>
                        </div>
                        <div class="preview-image" id="previewImage" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                            <button type="button" class="remove-photo" id="removePhoto">×</button>
                        </div>
                    </div>
                    
                    <div class="form-fields">
                        <div class="input-group">
                            <input type="text" id="adminName" placeholder="Name" required>
                        </div>
                        <div class="input-group">
                            <input type="text" id="adminNIC" placeholder="NIC" required>
                        </div>
                        <div class="input-group">
                            <input type="email" id="adminEmail" placeholder="Email" required>
                        </div>
                        <div class="input-group">
                            <input type="tel" id="adminMobile" placeholder="Mobile Number" required>
                        </div>
                    </div>
                    
                    <div class="permissions">
                        <h4>Permissions</h4>
                        <div class="checkbox-group">
                            <label class="checkbox-item">
                                <input type="checkbox" id="addOfficers" checked>
                                <span class="checkmark"></span>
                                Add Officers
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" id="addClients" checked>
                                <span class="checkmark"></span>
                                Add Clients
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" id="scheduling">
                                <span class="checkmark"></span>
                                Scheduling
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" id="salaryAdjustment">
                                <span class="checkmark"></span>
                                Salary Adjustment
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" id="resolveIncidents">
                                <span class="checkmark"></span>
                                Resolve Incidents
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" id="publishAdvertisements">
                                <span class="checkmark"></span>
                                Publish Advertisements
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" id="updateProfiles">
                                <span class="checkmark"></span>
                                Update Profiles
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="create-btn">Create</button>
                </div>
            </form>
        </div>
    </div>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/admin/settings.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>