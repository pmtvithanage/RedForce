<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/home/getservice_style.css">

    <div class="container">
        <div class="form-container">
            <!-- Header Section -->
            <div class="header">
                <div class="title">Request Service</div>
                <div class="company-info">
                    <div class="company-name">Red Force Security Service</div>
                    <div class="logo">
                        <img src="<?php echo URL_ROOT; ?>/public/img/logo.png" alt="RED FORCE" class="logo-img">
                        
                    </div>
                </div>
            </div>

            <!-- Main Form Area -->
            <div class="main-form">
                <!-- Left Side - Logo Upload -->
                <div class="logo-upload-section">
                    <div class="logo-placeholder">
                        
                    </div>
                    <button class="upload-btn">Upload logo/photo</button>
                </div>

                <!-- Right Side - Input Fields -->
                <div class="input-fields">
                    <div class="input-group">
                        <label for="company-name">Company Name:</label>
                        <input type="text" id="company-name" placeholder="Enter company name">
                    </div>
                    <div class="input-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" placeholder="Enter email address">
                    </div>
                    <div class="input-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" placeholder="Enter phone number">
                    </div>
                    <div class="input-group">
                        <label for="owner-name">Owner's Name:</label>
                        <input type="text" id="owner-name" placeholder="Enter owner's name">
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-section">
                <div class="table-header">
                    <h3>Service Sites</h3>
                    <button class="add-site-btn" onclick="addNewRow()">Add Site</button>
                </div>
                <div class="table-container">
                    <table class="service-table" id="serviceTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Site Address</th>
                                <th>Requested Security Officers</th>
                                <th>Requested Care-Takers</th>
                                <th>Night/Day</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="text" class="site-address" value="" placeholder="Enter site address"></td>
                                <td><input type="number" class="security-officers" value="" min="0" max="100" placeholder="Select"></td>
                                <td><input type="number" class="care-takers" value="" min="0" max="100" placeholder="Select"></td>
                                <td>
                                    <select class="shift-type">
                                        <option value="Both">Both</option>
                                        <option value="Day">Day</option>
                                        <option value="Night">Night</option>
                                    </select>
                                </td>
                                <td><button class="remove-btn" onclick="removeRow(this)">Remove</button></td>
                            </tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="buttons">
                <button class="cancel-btn" onclick="cancel()">Go Back</button>
                <button class="submit-btn" onclick="submitForm()">Submit</button>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_ROOT; ?>/js/home/getservice.js"></script>
    
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>

