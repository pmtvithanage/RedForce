<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>


    <!-- Content will be loaded here -->
     <main class="main">
            <header class="main-header">
                <div class="page-title">
                    <i class="fas fa-gear"></i>
                    <span>Settings</span>
                </div>
                <div class="header-actions">
                    <button class="logout-btn" id="logoutBtn"><i class="fas fa-right-from-brain"></i> Logout</button>
                    <div class="profile-chip">
                        <i class="fas fa-user"></i>
                        <span>Care Taker</span>
                    </div>
                </div>
            </header>

            <section class="settings-wrapper">
                <div class="profile-circle">
                    <i class="fas fa-user"></i>
                    <button class="edit-avatar" id="editAvatar" title="Change Avatar"><i class="fas fa-pen"></i></button>
                </div>

                <div class="settings-card">
                                         <div class="setting-row">
                         <label>Care Taker Name</label>
                         <div class="value" id="nameValue">- Abesekara</div>
                         <button class="ghost-btn" data-edit="name"><span>Change Name</span></button>
                         <button class="icon-btn" data-edit="name" title="Edit"><i class="fas fa-pen"></i></button>
                     </div>

                     <div class="setting-row">
                         <label>Password</label>
                         <div class="value" id="passwordValue">- ••••••••</div>
                         <button class="ghost-btn" data-edit="password"><span>Change Password</span></button>
                         <button class="icon-btn" data-edit="password" title="Edit"><i class="fas fa-pen"></i></button>
                     </div>

                    <div class="setting-row">
                        <label>Contact Number</label>
                        <div class="value" id="contactValue">- 0112 112 112</div>
                        <button class="danger-btn" data-edit="contact"><span>Change Contact No</span></button>
                        <button class="icon-btn" data-edit="contact" title="Edit"><i class="fas fa-pen"></i></button>
                    </div>

                                         <div class="setting-row">
                         <label>Email</label>
                         <div class="value" id="emailValue">- abesekara@hotmail.com</div>
                         <button class="primary-btn" data-edit="email"><span>Change Email</span></button>
                         <button class="icon-btn" data-edit="email" title="Edit"><i class="fas fa-pen"></i></button>
                     </div>

                     <div class="setting-row read-only">
                         <label>Address</label>
                         <div class="value" id="addressValue">- 123 Main Street, Colombo 01, Sri Lanka</div>
                     </div>
                </div>
            </section>
        </main>
    </div>

    <div id="toast" class="toast" role="status" aria-live="polite"></div>

    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>