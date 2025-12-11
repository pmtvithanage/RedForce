<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
/* ==== GLOBAL UI UPGRADE ==== */
:root{
    --primary:#a40000;
    --soft-text:#555;
    --border:#e6e6e6;
    --card-shadow:0 4px 14px rgba(0,0,0,0.10);
    --hover-shadow:0 8px 24px rgba(0,0,0,0.18);
    --radius:14px;
}

/* ======================================================
    PAGE WRAPPER
======================================================*/
.client-profile-container{
    width:100%;
    padding:25px 30px;
}

/* ======================================================
    TOP PROFILE SECTION
======================================================*/
.top-client-profile{
    background:#fff;
    padding:30px;
    border-radius:var(--radius);
    box-shadow:var(--card-shadow);
    display:flex;
    align-items:center;
    gap:40px;
    margin-bottom:35px;
    transition:.3s;
}
.top-client-profile:hover{
    box-shadow:var(--hover-shadow);
}

/* ---------- CLIENT LOGO IMAGE (NOW PERFECTLY CENTER) ---------- */
.top-client-profile img{
   
    height:200px;
    object-fit:cover;
    border-radius:12px;
    display:block;
}

/* Back Button */
.tertiary-btn{
    
    position:relative;
    top:-110px;
    left:0;
    display:flex;
    gap:6px;
    align-items:center;
}



/* ---------- RIGHT DETAILS COLUMN -------- */
.client-profile-info{
    padding-left:50px;
    border-left:2px solid var(--primary);
}

/* Heading */
.client-profile-info h2{
    font-size:32px;
    font-weight:900;
    margin-bottom:12px;
}

/* Each Detail Row */
.client-profile-info p{
    font-size:15px;
    margin:10px 0;
    color:#333;
    position:relative;
    padding-left:12px;
    display:flex;
    align-items:center;
    gap:10px;
}




/* ======================================================
    SEARCH BAR
======================================================*/
.search-wrap{
    position:relative;
    width:100%;
    max-width:500px;
    margin:0 auto 25px;
}
.search{
    width:100%;
    height:42px;
    border:1px solid #cfcfcf;
    border-radius:10px;
    padding:0 38px;
    font-size:15px;
    outline:none;
}
.search:focus{
    border-color:var(--primary);
}
.search-icon{
    position:absolute;
    left:12px;
    top:50%;
    transform:translateY(-50%);
    font-size:18px;
    color:#999;
}


/* ======================================================
    BOTTOM SITE CARDS GRID
======================================================*/
.bottom-cards{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(260px,1fr));
    gap:25px;
}

/* ----- CARD BOX DESIGN ------ */
.site-card{
    background:#fff;
    padding:20px;
    border-radius:var(--radius);
    box-shadow:var(--card-shadow);
    display:flex;
    flex-direction:column;
    transition:.3s;
}
.site-card:hover{
    transform:translateY(-6px);
    box-shadow:var(--hover-shadow);
}

/* ----- SITE IMAGE ------ */
.site-img{
    width:100%;
    height:160px;
    border-radius:var(--radius);
    object-fit:cover;
    margin-bottom:15px;
}

/* Title */
.site-card h3{
    font-size:18px;
    font-weight:700;
    margin-bottom:8px;
}

/* Description Text */
.site-card p{
    font-size:14px;
    color:var(--soft-text);
    margin:3px 0;
}

/* Button */
.view-btn{
    margin-top:auto;
    background:var(--primary);
    border:none;
    color:#fff;
    padding:10px;
    text-align:center;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
}
.view-btn:hover{
    opacity:.85;
    transform:scale(1.05);
}


/* ======================================================
    RESPONSIVE OPTIMIZATION
======================================================*/
@media(max-width:950px){
    .top-client-profile{
        flex-direction:column;
        text-align:center;
        padding-top:60px;
    }
    .top-client-profile img{
        margin:auto;
    }
    .client-profile-info{
        border-left:none;
        padding-left:0;
        margin-top:20px;
    }
}

@media(max-width:600px){
    .bottom-cards{
        grid-template-columns:1fr;
    }
}

/* Smooth Scrollbar */
::-webkit-scrollbar{ width:6px; }
::-webkit-scrollbar-thumb{
    background:var(--primary);
    border-radius:10px;
}
</style>

<!-- Content will be loaded here -->

<div class="client-profile-container">
    <div class="top-client-profile">
        <button class="tertiary-btn" style="display:flex; width:100px; align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/clients'"> 
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            Back
        </button>
        <img src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo $client->profile_image; ?>" alt="Client Logo">
        
        <div class="client-profile-info">
            <h2><?php echo $client->name?></h2>


            <p><span class="material-symbols-outlined info-icon">call</span>
                <strong>Phone:</strong> <?php echo $client->phone_number?>
            </p>

            <p><span class="material-symbols-outlined info-icon">mail</span>
                <strong>Email:</strong> <?php echo $client->email?>
            </p>

            <p><span class="material-symbols-outlined info-icon">person</span>
                <strong>Contact Person:</strong> <?php echo $client->contact_person_name?>
            </p>

            <p><span class="material-symbols-outlined info-icon">event</span>
                <strong>Added Date:</strong> <?php echo time_convert($client->created_at)?>
            </p>
        </div>
        
        
    </div>

    <button style="float: right;" class="secondary-btn" id="addSiteBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/addsite/<?php echo $client->id?>'">Add Site</button>

        <div class="search-wrap">
            <input id="searchInput" class="search" type="text" placeholder="Search" />
            <span class="search-icon"><span class="material-symbols-outlined">search</span></span>
        </div>
        
      <div class="bottom-cards">

        <?php foreach($data['sites'] as $site): ?>
                <div class="site-card">

                <img class="site-img" src="<?php echo URL_ROOT; ?>/uploads/siteImages/<?php echo $site->image?>" alt="Site Image">

                    <h3><?php echo $site->site_name?></h3>

                    <p><strong>Location:</strong> <?php echo $site->address?></p>
                    <p><strong>City:</strong> <?php echo $site->city?></p>
                    <p><strong>phone_number:</strong> <?php echo $site->phone_number?></p>
                    <p><strong>Last Updated:</strong> <?php echo time_convert($site->updated_at)?> </p>

                    <button class="view-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/viewsites/<?php echo $site->id; ?>'">
                        View Site
                    </button>
                </div>
        <?php endforeach; ?>
            
      </div>

  </div>



     <!--
    <div class="client-profile-container">

      
      <div class="left-client-profile">
          <img src="<?php echo URL_ROOT; ?>/img/client_logo.png" class="client-logo" alt="Client Logo">

          <div class="client-profile-info">
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