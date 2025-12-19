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
        width:60%;
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
.form-container {
    display: flex;
    margin: 20px;
    width: 95%;
}

.left-side {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.left-side img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: 8px;
}


</style>


<button class="tertiary-btn" style="display:flex; width:100px; margin:20px;align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/officers'"> 
    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
    Back
</button>

<!-- Tabs -->
<div class="tabs">
    <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/porecruitment'">Premise Officers</button>
    <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/mrrecruitment'">Mobile Riders</button>
    <button class="tab primary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/ctrecruitment'">Care-Takers</button>
</div>

<!-- New Job Details Section -->
<div class="form-container">
    <div class="left-side">
        <img src="<?php echo URL_ROOT; ?>/img/care-taker.png" alt="Care-Taker">
    </div>
    <form class="job-form" action="<?php echo URL_ROOT; ?>/admin/ctrecruitment" method="post">
        <div class="input-group">
            <label>Job Description</label>
            <textarea type="text" name="description" placeholder="Example – Responsible for ensuring site security, patrolling, access monitoring..."><?php echo $data['description'] ?? '';?></textarea>
        </div>

        <div class="input-group">
            <label>Qualifications Required</label>
            <textarea type="text" name="qualifications" placeholder="Example – Minimum 1 year experience, Physical fitness, Good communication skills..."><?php echo $data['qualifications'] ?? '';?></textarea>
        </div>

        <div class="input-group">
            <label>Due Date</label>
            <input type="date" name="due_date" id="dueDate" value="<?php echo $data['due_date'] ?? ''; ?>" required>
        </div>

        <div class="form-actions">
            <?php if (isset($data['completed']) && $data['completed'] == 'true' && isset($data['status']) && $data['status'] == 'closed'): ?>
                <button class="primary-btn" style="margin-right: 45%;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/changeStatus/ct/open'">Open Application</button>
            <?php endif; ?>

            <?php if (isset($data['completed']) && $data['completed'] == 'true' && isset($data['status']) && $data['status'] == 'open'): ?>
                <button class="primary-btn" style="margin-right: 45%;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/changeStatus/ct/closed'">Close Application</button>
            <?php endif; ?>
            <button type="submit" class="primary-btn" name="submit">Save</button>
            <button class="secondary-btn" type="reset" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/delete_job_application/ct/'">Clear</button>
        </div>
    </form>
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