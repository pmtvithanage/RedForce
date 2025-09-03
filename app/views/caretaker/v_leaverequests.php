<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>


    <!-- Content will be loaded here -->
      <!-- Main Content Area -->
      <main class="main-content">
          <!-- Top Header -->
          <header class="top-header">
              <div class="header-left">
                  <h2><i class="fas fa-check"></i> Request Leave</h2>
              </div>
              <div class="header-right">
                  <div class="user-profile">
                      <i class="fas fa-user"></i>
                      <span>Care Taker</span>
                  </div>
              </div>
          </header>

            <!-- Request Leave Form -->
            <section class="request-leave-section">
                <h2>Request Leave</h2>
                <form id="leaveForm" class="leave-form">
                    <div class="form-group">
                        <label for="leaveType">Leave Type</label>
                        <select id="leaveType" name="leaveType" required>
                            <option value="">Select leave type</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Vacation">Vacation</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="leaveReason">Leave Reason</label>
                        <textarea id="leaveReason" name="leaveReason" placeholder="Enter leave reason"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="startDate">Starting Date</label>
                            <div class="date-input-wrapper">
                                <input type="text" id="startDate" name="startDate" placeholder="Select start date" class="date-picker" readonly>
                                <div class="inline-calendar" id="startCalendar">
                                    <div class="calendar-header">
                                        <button class="calendar-nav prev" onclick="changeMonth('startCalendar', -1)"><i class="fas fa-chevron-left"></i></button>
                                        <span class="calendar-title" id="startCalendarTitle">December 2025</span>
                                        <button class="calendar-nav next" onclick="changeMonth('startCalendar', 1)"><i class="fas fa-chevron-right"></i></button>
                                    </div>
                                    <div class="calendar-weekdays">
                                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                                    </div>
                                    <div class="calendar-days" id="startCalendarDays"></div>
                                </div>
                                <i class="fas fa-calendar-alt calendar-icon" onclick="toggleCalendar('startCalendar')"></i>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="endDate">End Date</label>
                            <div class="date-input-wrapper">
                                <input type="text" id="endDate" name="endDate" placeholder="Select end date" class="date-picker" readonly>
                                <div class="inline-calendar" id="endCalendar">
                                    <div class="calendar-header">
                                        <button class="calendar-nav prev" onclick="changeMonth('endCalendar', -1)"><i class="fas fa-chevron-left"></i></button>
                                        <span class="calendar-title" id="endCalendarTitle">December 2025</span>
                                        <button class="calendar-nav next" onclick="changeMonth('endCalendar', 1)"><i class="fas fa-chevron-right"></i></button>
                                    </div>
                                    <div class="calendar-weekdays">
                                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                                    </div>
                                    <div class="calendar-days" id="endCalendarDays"></div>
                                </div>
                                <i class="fas fa-calendar-alt calendar-icon" onclick="toggleCalendar('endCalendar')"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="leaveProof">Leave proves</label>
                        <div class="file-upload">
                            <button type="button" id="attachFile" class="attach-btn">
                                <i class="fas fa-file"></i>
                                Attach File
                            </button>
                            <input type="file" id="fileInput" hidden>
                            <span id="fileName" class="file-name"></span>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">Request Leave</button>
                </form>
            </section>

            <!-- Leave History -->
            <section class="leave-history-section">
                <h2>Leave History</h2>
                <div class="table-container">
                    <table class="leave-table">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Reason</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Proof</th>
                            </tr>
                        </thead>
                        <tbody id="leaveHistoryBody">
                            <tr>
                                <td>Sick Leave</td>
                                <td>Fever</td>
                                <td>20/08/2025</td>
                                <td>22/08/2025</td>
                                <td><span class="status approved">Approved</span></td>
                                <td><button class="view-file-btn">View File</button></td>
                            </tr>
                            <tr>
                                <td>Annual Leave</td>
                                <td>Family Trip</td>
                                <td>01/06/2025</td>
                                <td>05/06/2025</td>
                                <td><span class="status pending">Pending</span></td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>