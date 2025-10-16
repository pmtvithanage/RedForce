document.addEventListener('DOMContentLoaded', function() {
    const monthSelect = document.getElementById('monthSelect');
    const yearSelect = document.getElementById('yearSelect');
    const calendarBody = document.getElementById('calendarBody');
    const shiftDetails = document.getElementById('shiftDetails');
    const selectedDateSpan = document.getElementById('selectedDate');
    const shiftContent = document.getElementById('shiftContent');
    
    let selectedDay = null;
    
    // Sample shift data - Replace with actual data from your backend
    const shiftData = {
        '2024-11-09': {
            location: 'People\'s Bank PLC - Kandy Branch',
            time: '08:00 AM - 06:00 PM',
            type: 'Day Shift',
            supervisor: 'John Silva',
            notes: 'Please arrive 15 minutes early for briefing. Remember to bring your ID card and uniform.'
        },
        '2024-11-16': {
            location: 'Commercial Bank - Colombo Main',
            time: '10:00 PM - 06:00 AM',
            type: 'Night Shift',
            supervisor: 'Naduni Senanayake',
            notes: 'Night shift requires extra security protocols. Contact supervisor for emergency procedures.'
        },
        '2024-11-23': {
            location: 'People\'s Bank PLC - Kandy Branch',
            time: '08:00 AM - 06:00 PM',
            type: 'Day Shift',
            supervisor: 'John Silva',
            notes: 'Regular day shift. Monitor main entrance and customer area.'
        },
        '2024-11-30': {
            location: 'Bank of Ceylon - Galle Road',
            time: '02:00 PM - 10:00 PM',
            type: 'Evening Shift',
            supervisor: 'Manager Williams',
            notes: 'Evening shift covers peak hours. Pay attention to cash transport timing.'
        }
    };
    
    function generateCalendar(month, year) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1; // Monday = 0
        
        const today = new Date();
        const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;
        const todayDate = today.getDate();
        
        calendarBody.innerHTML = '';
        
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
            
            // Check if there's a shift on this day
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            if (shiftData[dateString]) {
                dayElement.classList.add('has-shift');
                const shiftIndicator = document.createElement('div');
                shiftIndicator.className = 'shift-indicator';
                shiftIndicator.textContent = 'Shift';
                dayElement.appendChild(shiftIndicator);
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
        
        // Add click event for other month days too
        if (isOtherMonth) {
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            dayElement.addEventListener('click', () => selectDate(dateString, day, month, year));
        }
        
        return dayElement;
    }
    
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
        
        // Show shift details or no shift message
        showShiftDetails(dateString);
        
        selectedDay = {day, month, year, dateString};
    }
    
    function showShiftDetails(dateString) {
        const shift = shiftData[dateString];
        const shiftInfoTemplate = document.getElementById('shiftInfoTemplate');
        const noShiftTemplate = document.getElementById('noShiftTemplate');
        
        // Clear current content
        shiftContent.innerHTML = '';
        
        if (shift) {
            // Clone and populate shift info template
            const shiftInfo = shiftInfoTemplate.cloneNode(true);
            shiftInfo.style.display = 'block';
            shiftInfo.id = '';
            
            shiftInfo.querySelector('#shiftLocation').textContent = shift.location;
            shiftInfo.querySelector('#shiftTime').textContent = shift.time;
            shiftInfo.querySelector('#shiftType').textContent = shift.type;
            shiftInfo.querySelector('#shiftSupervisor').textContent = shift.supervisor;
            shiftInfo.querySelector('#shiftNotes').textContent = shift.notes;
            
            shiftContent.appendChild(shiftInfo);
        } else {
            // Clone and show no shift template
            const noShift = noShiftTemplate.cloneNode(true);
            noShift.style.display = 'block';
            noShift.id = '';
            
            shiftContent.appendChild(noShift);
        }
    }
    
    function updateCalendar() {
        const month = parseInt(monthSelect.value);
        const year = parseInt(yearSelect.value);
        generateCalendar(month, year);
        
        // Reset selection when changing months
        selectedDay = null;
        selectedDateSpan.textContent = 'Select a date';
        shiftContent.innerHTML = `
            <div class="no-selection">
                <div class="no-selection-icon">📅</div>
                <p>Click on a date to view your schedule</p>
            </div>
        `;
    }
    
    // Event listeners
    monthSelect.addEventListener('change', updateCalendar);
    yearSelect.addEventListener('change', updateCalendar);
    
    // Initialize calendar with current month
    const now = new Date();
    monthSelect.value = now.getMonth();
    yearSelect.value = now.getFullYear();
    updateCalendar();
});