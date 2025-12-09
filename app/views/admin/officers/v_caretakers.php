<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<!-- <span class="material-icons">face</span> -->

<style>
  

.container{
    width:90%;
    margin:40px auto;
}

/* Tabs */
.tabs{display:flex; gap:10px;}

.tabs button{
  margin-bottom:20px;
}

/* Table Card */
.table-card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 0 10px rgba(0,0,0,0.08);
    margin-top:-5px;
}

/* Search Bar */
.search-box{
    display:flex; align-items:center;
    gap:8px;
    border:1px solid #dadada;
    padding:10px 14px;
    border-radius:6px;
    margin-bottom:16px;
}

.search-box:focus-within{
    border-color:#a40000;
}
.search-box input{
    border:none; outline:none; width:100%;
    font-size:15px;
}

/* Table */
table{ width:100%; border-collapse:collapse;}
th,td{ padding:12px; font-size:14px; text-align:left;}
thead th{ background:#f9e9e9; }


/* Status Badges */
.badge{
    padding:5px 12px;
    border-radius:20px;
    color:white; font-size:12px;
    font-weight:600;
}
.green{ background:#4caf50;}
.red{ background:#e74c3c;}
.yellow{ background:#d4ad17; color:black;}

/* Stats Section */
.stats{
    display:flex; justify-content:center; gap:80px;
    margin:40px 0 20px;
    
}
.stat-item{
    text-align:center;
}
.stats h2{
    font-size:32px; font-weight:700;
    margin-bottom:0;
}
.stats p{
    color:#333;
    margin-top:3px;
}

/* Bottom Buttons */
.actions{
    display:flex; justify-content:center; gap:20px;
    margin-top:10px;
}
.actions button{
  width: 200px;
}

</style>
    <!-- Content will be loaded here -->
<div class="container">

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/officers'">Officers</button> 
        <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/mobileriders'">Mobile Riders</button>
        <button class="tab primary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/caretakers'">Care-Takers</button>
    </div>

    <!-- Table Card -->
    <div class="table-card">

        <div class="search-box">
            <span class="material-symbols-outlined">search</span>
            <input type="text" placeholder="Search">
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Officer ID</th>
                    <th>Officer</th>
                    <th>Rank</th>
                    <th>Status</th>
                    <th>Assignment</th>
                    <th>Rating</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td><td>PF231</td><td>Nuwan Perera</td>
                    <td>OIC</td><td><span class="badge green">On Duty</span></td>
                    <td>People's Bank PLC</td><td>1403</td>
                </tr>

                <tr>
                    <td>2</td><td>PF416</td><td>Kasun Silva</td>
                    <td>OIC</td><td><span class="badge red">On Leave</span></td>
                    <td>Cargills PLC</td><td>1399</td>
                </tr>

                <tr>
                    <td>3</td><td>PF664</td><td>Dilan Jayasuriya</td>
                    <td>OIC</td><td><span class="badge green">On Duty</span></td>
                    <td>Aitken Spence PLC</td><td>1382</td>
                </tr>

                <tr>
                    <td>4</td><td>PF220</td><td>Chamika Bandara</td>
                    <td>Level 4</td><td><span class="badge green">On Duty</span></td>
                    <td>Sri Lanka Telecom</td><td>1380</td>
                </tr>

                <tr>
                    <td>5</td><td>PF100</td><td>Suranga Kumara</td>
                    <td>OIC</td><td><span class="badge green">On Duty</span></td>
                    <td>Petroleum Corporation</td><td>1376</td>
                </tr>

                <tr>
                    <td>6</td><td>PF230</td><td>Tharindu Wickramasinghe</td>
                    <td>Level 3</td><td><span class="badge yellow">On Break</span></td>
                    <td>People's Bank PLC</td><td>1373</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Stats Section -->
    <div class="stats">
        <div class="stat-item">
            <h2>1434</h2>
            <p>Active Officers</p>
        </div>
        <div class="stat-item">
            <h2>947</h2>
            <p>Retired Officers</p>
        </div>
    </div>

    <!-- Bottom Buttons -->
    <div class="actions">
        <button class="tertiary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/porecruitment'">Open Recruitment</button>
        <button class="tertiary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/pending_officer_applications/all'">+ Add Officers</button>
    </div>

</div>

    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>