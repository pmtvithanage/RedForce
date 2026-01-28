<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>


    <!-- Content will be loaded here -->
    <button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="history.back()"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>