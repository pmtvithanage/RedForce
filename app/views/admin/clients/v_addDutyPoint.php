<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
    :root {
        --bg: #f0f2f5;
        --card: #fff;
        --muted: #606770;
        --accent: #a40000;
        --accent-plain: #e7f0ff;
        --container-padding: 18px;
        --shadow: 0 6px 18px rgba(20,20,40,0.06);
        --glass: rgba(255,255,255,0.8);
    }

    .form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .form-card {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: var(--shadow);
        margin-top: 20px;
    }

    .form-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .form-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .form-subtitle {
        color: var(--muted);
        margin-top: 8px;
        font-size: 14px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1a1a1a;
        font-size: 14px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #e1e5e9;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #fff;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .shift-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .shift-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .shift-title {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 16px;
    }

    .officer-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #fff;
        border-radius: 6px;
        margin-bottom: 10px;
        border: 1px solid #e1e5e9;
    }

    .officer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background: #e9ecef;
    }

    .officer-details {
        flex: 1;
    }

    .officer-name {
        font-weight: 600;
        font-size: 14px;
    }

    .officer-role {
        font-size: 12px;
        color: var(--muted);
    }

    .add-officer-btn {
        background: transparent;
        border: 1px dashed #e1e5e9;
        color: var(--accent);
        padding: 12px;
        border-radius: 6px;
        width: 100%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .add-officer-btn:hover {
        border-color: var(--accent);
        background: rgba(164, 0, 0, 0.02);
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        border: none;
        transition: all 0.2s;
    }

    .btn-primary {
        background: var(--accent);
        color: #fff;
    }

    .btn-primary:hover {
        background: #8a0000;
    }

    .btn-secondary {
        background: #fff;
        color: #1a1a1a;
        border: 1px solid #e1e5e9;
    }

    .btn-secondary:hover {
        background: #f8f9fa;
    }

    .tertiary-btn {
        background: #fff;
        border: 1px solid #e1e5e9;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }

    .required::after {
        content: " *";
        color: #dc2626;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkbox {
        width: 16px;
        height: 16px;
    }

    .time-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .supervisor-select {
        margin-bottom: 15px;
    }
</style>

<div class="shell" role="main">
    <button class="tertiary-btn" style="display:flex; width:100px; margin:20px;align-items:center;" onclick="history.back()"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>

    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h1 class="form-title">Add New Duty Point</h1>
                <p class="form-subtitle">Create a new duty point assignment with shift details and personnel</p>
            </div>

            <form id="addDutyPointForm">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <span class="material-symbols-outlined">location_on</span>
                        Basic Information
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="location" class="form-label required">Location Name</label>
                            <input type="text" id="location" name="location" class="form-input" placeholder="e.g., Main Entrance, Parking Area" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="form-label required">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-textarea" placeholder="Brief description of the duty point and responsibilities..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Day Shift Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <span class="material-symbols-outlined" style="color: #f59e0b;">wb_sunny</span>
                        Day Shift (08:00 - 20:00)
                    </h2>
                    
                    <div class="shift-section">
                        <!-- Day Shift Supervisor -->
                        <div class="form-group supervisor-select">
                            <label for="daySupervisor" class="form-label required">Day Shift Supervisor</label>
                            <select id="daySupervisor" name="daySupervisor" class="form-select" required>
                                <option value="">Select Supervisor</option>
                                <option value="1">John Smith</option>
                                <option value="2">Emma Thompson</option>
                                <option value="3">Michael Brown</option>
                            </select>
                        </div>

                        <!-- Day Shift Officers -->
                        <div class="form-group">
                            <label class="form-label">Day Shift Officers</label>
                            
                            <div class="officer-list" id="dayOfficersList">
                                <!-- Officer items will be added here dynamically -->
                            </div>
                            
                            <button type="button" class="add-officer-btn" onclick="addOfficer('day')">
                                <span class="material-symbols-outlined">add</span>
                                Add Officer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Night Shift Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <span class="material-symbols-outlined" style="color: #1e40af;">dark_mode</span>
                        Night Shift (20:00 - 08:00)
                    </h2>
                    
                    <div class="shift-section">
                        <!-- Night Shift Supervisor -->
                        <div class="form-group supervisor-select">
                            <label for="nightSupervisor" class="form-label required">Night Shift Supervisor</label>
                            <select id="nightSupervisor" name="nightSupervisor" class="form-select" required>
                                <option value="">Select Supervisor</option>
                                <option value="4">Sarah Johnson</option>
                                <option value="5">David Miller</option>
                                <option value="6">Lisa Garcia</option>
                            </select>
                        </div>

                        <!-- Night Shift Officers -->
                        <div class="form-group">
                            <label class="form-label">Night Shift Officers</label>
                            
                            <div class="officer-list" id="nightOfficersList">
                                <!-- Officer items will be added here dynamically -->
                            </div>
                            
                            <button type="button" class="add-officer-btn" onclick="addOfficer('night')">
                                <span class="material-symbols-outlined">add</span>
                                Add Officer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Additional Settings -->
                <div class="form-section">
                    <h2 class="section-title">
                        <span class="material-symbols-outlined">settings</span>
                        Additional Settings
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="priority" class="form-label">Priority Level</label>
                            <select id="priority" name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="equipment" class="form-label">Required Equipment</label>
                            <input type="text" id="equipment" name="equipment" class="form-input" placeholder="e.g., Radio, Flashlight, Vest">
                        </div>
                        
                        <div class="form-group full-width">
                            <div class="checkbox-group">
                                <input type="checkbox" id="notifications" name="notifications" class="checkbox" checked>
                                <label for="notifications" class="form-label">Enable shift change notifications</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">
                        <span class="material-symbols-outlined">close</span>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="material-symbols-outlined">save</span>
                        Create Duty Point
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script>
    let officerCounter = { day: 0, night: 0 };

    function addOfficer(shiftType) {
        const officerList = document.getElementById(`${shiftType}OfficersList`);
        const officerId = `${shiftType}_officer_${officerCounter[shiftType]++}`;
        
        const officerItem = document.createElement('div');
        officerItem.className = 'officer-item';
        officerItem.innerHTML = `
            <img src="<?= URL_ROOT ?>/img/avatar-placeholder.jpg" alt="Officer" class="officer-avatar">
            <div class="officer-details" style="flex: 1;">
                <select name="${shiftType}_officers[]" class="form-select" required>
                    <option value="">Select Officer</option>
                    <option value="7">Robert Davis</option>
                    <option value="8">Jennifer Wilson</option>
                    <option value="9">James Anderson</option>
                    <option value="10">Thomas White</option>
                </select>
            </div>
            <button type="button" class="tertiary-btn" onclick="this.parentElement.remove()" style="color: #dc2626;">
                <span class="material-symbols-outlined">delete</span>
            </button>
        `;
        
        officerList.appendChild(officerItem);
    }

    // Add initial officer for each shift
    document.addEventListener('DOMContentLoaded', function() {
        addOfficer('day');
        addOfficer('night');
    });

    // Form submission handler
    document.getElementById('addDutyPointForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Form validation
        const location = document.getElementById('location').value;
        const status = document.getElementById('status').value;
        const daySupervisor = document.getElementById('daySupervisor').value;
        const nightSupervisor = document.getElementById('nightSupervisor').value;
        
        if (!location || !status || !daySupervisor || !nightSupervisor) {
            alert('Please fill in all required fields.');
            return;
        }

        // Here you would typically send the form data to the server
        console.log('Form submitted successfully!');
        alert('Duty point created successfully!');
        
        // Redirect back to duty points list
        window.location.href = '<?= URL_ROOT ?>/admin/duty-points';
    });
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>