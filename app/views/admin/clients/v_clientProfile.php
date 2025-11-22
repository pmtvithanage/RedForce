<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

  <style>
/* MAIN LAYOUT */
.profile-container {
    display: flex;
    width: 100%;
}

/* LEFT FIXED PROFILE PANEL */
.left-profile {
    position: fixed;
    top: 100px;
    left: 300px;
    width: 650px;
    height: calc(100vh - 100px);
    overflow-y: auto;

    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);

    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

/* CLIENT LOGO */
.client-logo {
    height: 150px;
    object-fit: cover;
    margin-bottom: 15px;
    border: 3px solid #eee;
}

/* PROFILE INFO WRAPPER */
.profile-info {
    padding: 10px 0;
    border-top: 1px solid #eee;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.profile-info h2 {
    font-size: 30px;
    font-weight: 600;
    margin-bottom: 15px;
}

.profile-info p {
    margin: 0;
    font-size: 20px;
    color: #444;
    line-height: 1.4;
}

.profile-info p strong {
    font-weight: 600;
    color: #222;
}

/* RIGHT SIDE */
.right-cards {
    margin-left: 680px;
    padding: 20px;
    width: calc(100% - 420px);
}

/* SITE CARD */
.site-card {
    width: 100%;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    margin-bottom: 20px;
}

.site-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 10px;
}

.view-btn {
    margin-top: 10px;
    padding: 10px 15px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
.view-btn:hover {
    background: #0056b3;
}



/* ---------------------------------------------------
   RESPONSIVE SECTION
--------------------------------------------------- */

/* TABLETS (max-width: 1024px) */
@media (max-width: 1024px) {

    .left-profile {
        position: relative;
        width: 100%;
        left: 0;
        top: 0;
        height: auto;
        margin-bottom: 20px;
    }

    .right-cards {
        margin-left: 0;
        width: 100%;
    }

    .profile-container {
        flex-direction: column;
        align-items: center;
    }
}


/* MOBILE (max-width: 768px) */
@media (max-width: 768px) {

    .left-profile {
        width: 95%;
        padding: 15px;
    }

    .profile-info h2 {
        font-size: 24px;
    }

    .profile-info p {
        font-size: 16px;
    }

    .site-card {
        padding: 15px;
    }

    .site-img {
        height: 150px;
    }

    .view-btn {
        width: 100%;
    }
}


/* SMALL MOBILE (max-width: 480px) */
@media (max-width: 480px) {

    .left-profile {
        width: 100%;
        padding: 12px;
    }

    .client-logo {
        height: 120px;
    }

    .profile-info h2 {
        font-size: 20px;
    }

    .site-img {
        height: 130px;
    }
}

  </style>

    <!-- Content will be loaded here -->



    <div class="profile-container">

      <!-- LEFT SIDE -->
      <div class="left-profile">
          <img src="<?php echo URL_ROOT; ?>/img/logo.png" class="client-logo" alt="Client Logo">

          <div class="profile-info">
              <h2>Red Force</h2>

              <p><strong>Address:</strong> aaaaaaaaaaaaaadssssssssss</p>
              <p><strong>Phone:</strong> rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr</p>
              <p><strong>Email:</strong> errrrr rrrrrrrrrrrt  tttt</p>
              <p><strong>Contact Person:</strong> t3ewt4535v 45h6y56hgeryr</p>
              <p><strong>Added Date:</strong> 34rt234 w45gy4t54</p>
          </div>
      </div>

        <!-- RIGHT SIDE -->
      <div class="right-cards">

          
              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>2r42r422rwr 34t34rt43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/viewsites'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>rwqerwer r34r3w4rfw43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>2r42r422rwr 34t34rt43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>rwqerwer r34r3w4rfw43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>
          <div class="site-card">

            <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>2r42r422rwr 34t34rt43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>rwqerwer r34r3w4rfw43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>2r42r422rwr 34t34rt43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>rwqerwer r34r3w4rfw43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>2r42r422rwr 34t34rt43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>rwqerwer r34r3w4rfw43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>2r42r422rwr 34t34rt43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>

              <div class="site-card">

              <img src="<?php echo URL_ROOT; ?>/img/hero.png" class="site-img" alt="Site Image">

                  <h3>rwqerwer r34r3w4rfw43</h3>

                  <p><strong>Location:</strong> t434t35t 453yt4536t45t45</p>
                  <p><strong>Status:</strong> 324t35t 235t4y5rhg rtjnthjn</p>
                  <p><strong>Last Updated:</strong> fewrtwert </p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view'">
                      View Site
                  </button>
              </div>
      </div>

  </div>



     <!--
    <div class="profile-container">

      
      <div class="left-profile">
          <img src="<?php echo URL_ROOT; ?>/img/client_logo.png" class="client-logo" alt="Client Logo">

          <div class="profile-info">
              <h2><?php echo $data['client']->name; ?></h2>

              <p><strong>Address:</strong> <?php echo $data['client']->address; ?></p>
              <p><strong>Phone:</strong> <?php echo $data['client']->phone; ?></p>
              <p><strong>Email:</strong> <?php echo $data['client']->email; ?></p>
              <p><strong>Contact Person:</strong> <?php echo $data['client']->contact_person; ?></p>
              <p><strong>Added Date:</strong> <?php echo $data['client']->created_at; ?></p>
          </div>
      </div>

      
      <div class="right-cards">

          <?php foreach($data['sites'] as $site): ?>
              <div class="site-card">
                  <h3><?php echo $site->site_name; ?></h3>

                  <p><strong>Location:</strong> <?php echo $site->location; ?></p>
                  <p><strong>Status:</strong> <?php echo $site->status; ?></p>
                  <p><strong>Last Updated:</strong> <?php echo $site->updated_at; ?></p>

                  <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/sites/view/<?php echo $site->id; ?>'">
                      View Site
                  </button>
              </div>
          <?php endforeach; ?>

      </div>

  </div>
          -->
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>