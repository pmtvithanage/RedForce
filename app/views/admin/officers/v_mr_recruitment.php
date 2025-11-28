<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
    .tabs{
        margin-left:20px;
    }
    .job-form{
        margin:20px;
        background:#fff;
        padding:20px;
        border-radius:10px;
        width:95%;
        box-shadow:0 3px 10px rgba(0,0,0,0.1);
    }
    .input-group{
        margin-bottom:18px;
        display:flex;
        flex-direction:column;
    }
    .input-group label{
        font-weight:600;
        margin-bottom:5px;
    }
    .input-group textarea,
    .input-group input{
        padding:12px;
        border-radius:6px;
        border:1px solid #ccc;
        font-size:15px;
    }

    .input-group textarea{
        height:130px;
        resize:none;
    }

     .input-group input{
        width: 150px;
     }

    .input-group textarea:focus,
    .input-group input:focus{
        border-color:#a40000;
        outline:none;
    }

    .form-actions{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:15px;
}

</style>


<button class="tertiary-btn" style="display:flex; width:100px; margin:20px;align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/officers'"> 
    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
    Back
</button>

<!-- Tabs -->
<div class="tabs">
    <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/porecruitment'">Premise Officers</button>
    <button class="tab primary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/mrrecruitment'">Mobile Riders</button>
    <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/ctrecruitment'">Care-Takers</button>
</div>

<!-- New Job Details Section -->
<div class="job-form">
    <div class="input-group">
        <label>Job Description</label>
        <textarea placeholder="Example – Responsible for ensuring site security, patrolling, access monitoring..."></textarea>
    </div>

    <div class="input-group">
        <label>Qualifications Required</label>
        <textarea placeholder="Example – Minimum 1 year experience, Physical fitness, Good communication skills..."></textarea>
    </div>

    <div class="input-group">
        <label>Due Date</label>
        <input type="date" id="dueDate">
    </div>

    <div class="form-actions">
        <button class="primary-btn">Save</button>
        <button class="secondary-btn" type="reset">Clear</button>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script>
    const dateInput = document.getElementById("dueDate");
    const today = new Date().toISOString().split("T")[0];
    dateInput.min = today;
</script>


<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>