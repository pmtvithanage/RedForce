document.addEventListener('DOMContentLoaded', function() {
    const calendarBody = document.getElementById('calendarBody');
    const currentMonthYearSpan = document.getElementById('currentMonthYear');
    const selectedDateSpan = document.getElementById('selectedDate');
    const shiftContent = document.getElementById('shiftContent');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let selectedDay = null;
    
    // Process assignment data to create work day mappings
    const workDays = new Set();
    const shiftInfo = {};
    
    if (typeof assignmentData !== 'undefined' && assignmentData) {
        console.log('Processing assignments:', assignmentData);
        assignmentData.forEach(assignment => {
            console.log('Processing assignment:', assignment);
            const startDate = parseISODateLocal(assignment.assignment_start);
            const endDate = assignment.assignment_end
                ? parseISODateLocal(assignment.assignment_end)
                : new Date(startDate);

            if (!isValidDate(startDate) || !isValidDate(endDate)) {
                return;
            }

            // Guard against malformed periods where end is before start.
            if (endDate < startDate) {
                return;
            }
            
            console.log('Start date:', startDate, 'End date:', endDate);
            
            // Add only days inside the explicit service period.
            let currentDay = new Date(startDate);
            let daysAdded = 0;
            while (currentDay <= endDate) {
                const dateStr = formatDateForKey(currentDay);
                workDays.add(dateStr);
                
                // Store shift info for this date
                if (!shiftInfo[dateStr]) {
                    shiftInfo[dateStr] = {
                        shift_type: assignment.shift_type,
                        site_name: assignment.site_name,
                        address: assignment.address,
                        city: assignment.city
                    };
                }
                
                currentDay.setDate(currentDay.getDate() + 1);
                daysAdded++;
            }
            console.log('Added', daysAdded, 'work days for this assignment');
        });
        console.log('Total work days:', workDays.size);
        console.log('Work days array:', Array.from(workDays));
    }
    
    // Process leave data to create leave day mappings
    const leaveDays = new Set();
    const leaveInfo = {};
    const attendanceByDate = {};
    
    if (typeof leaveDatesData !== 'undefined' && leaveDatesData) {
        leaveDatesData.forEach(leave => {
            const startDate = parseISODateLocal(leave.start_date);
            const endDate = parseISODateLocal(leave.end_date);

            if (!isValidDate(startDate) || !isValidDate(endDate) || endDate < startDate) {
                return;
            }
            
            // Add all days in the leave period
            let currentDay = new Date(startDate);
            while (currentDay <= endDate) {
                const dateStr = formatDateForKey(currentDay);
                leaveDays.add(dateStr);
                
                // Store leave info for this date
                if (!leaveInfo[dateStr]) {
                    leaveInfo[dateStr] = {
                        leave_type: leave.leave_type,
                        reason: leave.reason
                    };
                }
                
                currentDay.setDate(currentDay.getDate() + 1);
            }
        });
    }

    if (typeof attendanceData !== 'undefined' && attendanceData) {
        attendanceData.forEach((row) => {
            if (row.attendance_date) {
                attendanceByDate[String(row.attendance_date)] = String(row.status || '').toLowerCase();
            }
        });
    }
    
    // Helper function to format date as YYYY-MM-DD
    function formatDateForKey(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function parseISODateLocal(value) {
        if (!value) {
            return new Date('invalid');
        }
        const [y, m, d] = String(value).split('-').map(Number);
        return new Date(y, (m || 1) - 1, d || 1);
    }

    function isValidDate(date) {
        return date instanceof Date && !Number.isNaN(date.getTime());
    }

    function isPastDate(dateString) {
        const todayKey = formatDateForKey(new Date());
        return dateString < todayKey;
    }

    function getAttendanceBadgeMarkup(attendanceStatus) {
        if (!attendanceStatus) {
            return '';
        }

        const normalized = String(attendanceStatus).toLowerCase();
        if (normalized === 'present') {
            return '<span class="status-badge attendance-badge attendance-present">Present</span>';
        }
        if (normalized === 'absent') {
            return '<span class="status-badge attendance-badge attendance-absent">Absent</span>';
        }

        return '';
    }
    
    // Generate calendar for a specific month and year
    function generateCalendar(month, year) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1; // Monday = 0
        
        const today = new Date();
        const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;
        const todayDate = today.getDate();
        
        calendarBody.innerHTML = '';
        
        // Update month/year display
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        currentMonthYearSpan.textContent = `${monthNames[month]} ${year}`;
        
        // Add previous month's trailing days
        const prevMonth = month === 0 ? 11 : month - 1;
        const prevYear = month === 0 ? year - 1 : year;
        const prevMonthDays = new Date(prevYear, prevMonth + 1, 0).getDate();
        
        for (let i = startingDayOfWeek - 1; i >= 0; i--) {
            const dayElement = createDayElement(prevMonthDays - i, true, prevMonth, prevYear);
            calendarBody.appendChild(dayElement);
        }
        
        // Add current month's days
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = createDayElement(day, false, month, year);
            
            // Check if it's today
            if (isCurrentMonth && day === todayDate) {
                dayElement.classList.add('today');
            }
            
            // Check if there's a work shift on this day
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            // Debug logging for the first few days
            if (day <= 3) {
                console.log('Checking date:', dateString, 'Is work day?', workDays.has(dateString), 'Is leave day?', leaveDays.has(dateString));
            }
            
            // Check if it's a leave day (red) - takes priority
            if (leaveDays.has(dateString)) {
                dayElement.classList.add('leave-day');
            } 
            // Check if it's a work day (green)
            else if (workDays.has(dateString)) {
                const attendanceStatus = attendanceByDate[dateString] || null;
                const inferredAbsent = isPastDate(dateString) && attendanceStatus !== 'present';
                if (inferredAbsent) {
                    dayElement.classList.add('absent-day');
                } else {
                    dayElement.classList.add('work-day');
                }
            }
            
            // Add click event to show shift details
            dayElement.addEventListener('click', () => selectDate(dateString, day, month, year));
            
            calendarBody.appendChild(dayElement);
        }
        
        // Add next month's leading days
        const totalCells = calendarBody.children.length;
        const remainingCells = 42 - totalCells; // 6 rows × 7 days = 42 cells
        const nextMonth = month === 11 ? 0 : month + 1;
        const nextYear = month === 11 ? year + 1 : year;
        
        for (let day = 1; day <= remainingCells; day++) {
            const dayElement = createDayElement(day, true, nextMonth, nextYear);
            calendarBody.appendChild(dayElement);
        }
    }
    
    // Create a day element
    function createDayElement(day, isOtherMonth, month, year) {
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';
        if (isOtherMonth) {
            dayElement.classList.add('other-month');
        }
        
        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';
        dayNumber.textContent = day;
        dayElement.appendChild(dayNumber);
        
        // Keep other-month cells neutral (no work/leave coloring).
        if (isOtherMonth) {
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            dayElement.addEventListener('click', () => selectDate(dateString, day, month, year));
        }
        
        return dayElement;
    }
    
    // Select a date and show details
    function selectDate(dateString, day, month, year) {
        // Remove previous selection
        const previousSelected = document.querySelector('.calendar-day.selected');
        if (previousSelected) {
            previousSelected.classList.remove('selected');
        }
        
        // Add selection to clicked day
        const clickedDay = event.currentTarget;
        clickedDay.classList.add('selected');
        
        // Update selected date display
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        const formattedDate = `${day} ${monthNames[month]} ${year}`;
        selectedDateSpan.textContent = formattedDate;
        
        // Fetch and show shift details via AJAX
        fetchShiftDetails(dateString);
        
        selectedDay = {day, month, year, dateString};
    }
    
    // Fetch shift details from server
    function fetchShiftDetails(dateString) {
        console.log('Fetching shift details for:', dateString);
        console.log('Base URL:', baseURL);
        console.log('Full URL:', baseURL + '/premiseOfficer/getShiftDetails');
        
        // Show loading state
        shiftContent.innerHTML = `
            <div class="loading-state">
                <span class="material-icons loading-icon">hourglass_empty</span>
                <p>Loading shift details...</p>
            </div>
        `;
        
        // Make AJAX request
        fetch(baseURL + '/premiseOfficer/getShiftDetails', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'date=' + encodeURIComponent(dateString)
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            console.log('Response headers:', response.headers.get('content-type'));
            
            // Get the response text first to see what we're actually getting
            return response.text().then(text => {
                console.log('Response text:', text);
                
                if (!response.ok) {
                    console.error('Error response body:', text);
                    throw new Error('Network response was not ok: ' + response.status);
                }
                
                // Try to parse as JSON
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', e);
                    console.error('Response was:', text.substring(0, 500));
                    throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                }
            });
        })
        .then(data => {
            console.log('Shift details response:', data);
            if (data.success) {
                if (data.type === 'leave') {
                    showLeaveDetails(data);
                } else if (data.type === 'shift') {
                    showShiftDetails(data);
                } else {
                    showNoShift(data);
                }
            } else {
                console.error('Server returned error:', data.message);
                showError(data.message || 'Failed to load shift details');
            }
        })
        .catch(error => {
            console.error('Error fetching shift details:', error);
            showError('Failed to load shift details. Please try again.');
        });
    }
    
    // Show shift details
    function showShiftDetails(data) {
        const attendanceBadge = getAttendanceBadgeMarkup(data.attendance_status);
        shiftContent.innerHTML = `
            <div class="shift-card">
                <div class="shift-status">
                    <span class="status-badge work-badge">Scheduled Work Day</span>
                    ${attendanceBadge}
                </div>
                
                <div class="shift-details-grid">
                    <div class="detail-item">
                        <span class="material-icons detail-icon">location_on</span>
                        <div class="detail-content">
                            <span class="detail-label">Location</span>
                            <span class="detail-value">${data.location}</span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <span class="material-icons detail-icon">map</span>
                        <div class="detail-content">
                            <span class="detail-label">Address</span>
                            <span class="detail-value">${data.address}</span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <span class="material-icons detail-icon">access_time</span>
                        <div class="detail-content">
                            <span class="detail-label">Shift Time</span>
                            <span class="detail-value">${data.time}</span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <span class="material-icons detail-icon">work</span>
                        <div class="detail-content">
                            <span class="detail-label">Shift Type</span>
                            <span class="detail-value">${data.shift_type}</span>
                        </div>
                    </div>
                    
                    ${data.client ? `
                    <div class="detail-item">
                        <span class="material-icons detail-icon">person</span>
                        <div class="detail-content">
                            <span class="detail-label">Client</span>
                            <span class="detail-value">${data.client}</span>
                        </div>
                    </div>
                    ` : ''}
                </div>
                
                <div class="shift-notes">
                    <div class="notes-header">
                        <span class="material-icons">note</span>
                        <h4>Additional Notes</h4>
                    </div>
                    <p>${data.notes}</p>
                </div>
            </div>
        `;
    }
    
    // Show leave details
    function showLeaveDetails(data) {
        const attendanceBadge = getAttendanceBadgeMarkup(data.attendance_status);
        shiftContent.innerHTML = `
            <div class="leave-card">
                <div class="leave-status">
                    <span class="status-badge leave-badge">Approved Leave</span>
                    ${attendanceBadge}
                </div>
                
                <div class="leave-icon-container">
                    <span class="material-icons leave-icon">beach_access</span>
                </div>
                
                <div class="leave-details">
                    <div class="detail-item">
                        <span class="material-icons detail-icon">event_busy</span>
                        <div class="detail-content">
                            <span class="detail-label">Leave Type</span>
                            <span class="detail-value">${data.leave_type}</span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <span class="material-icons detail-icon">description</span>
                        <div class="detail-content">
                            <span class="detail-label">Reason</span>
                            <span class="detail-value">${data.reason}</span>
                        </div>
                    </div>
                </div>
                
                <div class="leave-message">
                    <p>You are on approved leave for this date. Enjoy your time off!</p>
                </div>
            </div>
        `;
    }
    
    // Show no shift message
    function showNoShift(data = null) {
        const attendanceBadge = getAttendanceBadgeMarkup(data?.attendance_status);
        shiftContent.innerHTML = `
            <div class="no-shift-card">
                <span class="material-icons no-shift-icon">free_breakfast</span>
                <h4>No Shift Scheduled</h4>
                <p>You have no scheduled shifts for this date.</p>
                ${attendanceBadge ? `<div style="margin-bottom:10px;">${attendanceBadge}</div>` : ''}
                <div class="day-off-badge">
                    <span class="material-icons">beach_access</span>
                    Day Off
                </div>
            </div>
        `;
    }
    
    // Show error message
    function showError(message) {
        shiftContent.innerHTML = `
            <div class="error-state">
                <span class="material-icons error-icon">error_outline</span>
                <p>${message}</p>
            </div>
        `;
    }
    
    // Navigate to previous month
    function previousMonth() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar(currentMonth, currentYear);
        resetSelection();
    }
    
    // Navigate to next month
    function nextMonth() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar(currentMonth, currentYear);
        resetSelection();
    }
    
    // Reset selection when changing months
    function resetSelection() {
        selectedDay = null;
        selectedDateSpan.textContent = 'Select a date';
        shiftContent.innerHTML = `
            <div class="no-selection">
                <span class="material-icons no-selection-icon">event</span>
                <p>Click on a date to view your schedule</p>
            </div>
        `;
    }
    
    // Event listeners
    prevMonthBtn.addEventListener('click', previousMonth);
    nextMonthBtn.addEventListener('click', nextMonth);
    
    // Initialize calendar with current month
    generateCalendar(currentMonth, currentYear);
});