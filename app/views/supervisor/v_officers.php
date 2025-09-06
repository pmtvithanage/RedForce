<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/officers.style.css">


    <!-- Content will be loaded here -->
     <!-- Main -->
        
            <section class="feedback-section">
                <div class="feedback-card">
                    <div class="officer-profile">
                        <div class="officer-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="officer-info">
                            <div class="info-row">
                                <label>Name:</label>
                                <span>M.W.Viviane Perera</span>
                            </div>
                            <div class="info-row">
                                <label>Officer ID:</label>
                                <span>PF231</span>
                            </div>
                            <div class="info-row">
                                <label>Rank:</label>
                                <span>OIC</span>
                            </div>
                            <div class="info-row">
                                <label>Location:</label>
                                <span>People's Bank PLC, No. 112, Sir Chittampalam A. Gardiner Mawatha, Colombo 2</span>
                            </div>
                            <div class="info-row">
                                <label>Rating:</label>
                                <span class="rating-value">1403</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="feedback-form">
                    <h3>Feedback Officer</h3>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" placeholder="Enter your feedback description..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Rating</label>
                        <div class="star-rating" id="starRating">
                            <i class="fas fa-star" data-rating="1"></i>
                            <i class="fas fa-star" data-rating="2"></i>
                            <i class="fas fa-star" data-rating="3"></i>
                            <i class="fas fa-star" data-rating="4"></i>
                            <i class="fas fa-star" data-rating="5"></i>
                        </div>
                    </div>
                    <button class="submit-btn" id="submitFeedback">Submit Feedback</button>
                </div>
            </section>
        </main>
    </div>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
<script src="<?= URL_ROOT ?>/js/caretaker/officers.js"></script>