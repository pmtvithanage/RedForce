// Leave Request Calendar - Opens Upward
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
const calendars = {};

document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const attachFileBtn = document.getElementById('attachFile');
    const fileName = document.getElementById('fileName');
    
    // File upload button
    if (attachFileBtn && fileInput && fileName) {
        attachFileBtn.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;
                fileName.style.display = 'inline';
            } else {
                fileName.textContent = '';
                fileName.style.display = 'none';
            }
        });
    }
    
    // Initialize calendars
    initCalendar('startCalendar', 'startDate');
    initCalendar('endCalendar', 'endDate');
    
    // Calendar icon clicks
    document.querySelectorAll('.calendar-icon').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            const wrapper = this.parentElement;
            const calendar = wrapper.querySelector('.inline-calendar');
            const isActive = calendar.classList.contains('active');
            
            // Close all calendars
            document.querySelectorAll('.inline-calendar').forEach(cal => cal.classList.remove('active'));
            
            // Toggle this calendar
            if (!isActive) {
                calendar.classList.add('active');
            }
        });
    });
    
    // Close calendar when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.date-input-wrapper')) {
            document.querySelectorAll('.inline-calendar').forEach(cal => cal.classList.remove('active'));
        }
    });
    
    // Navigation buttons
    document.querySelectorAll('.calendar-nav').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const calendarId = this.dataset.calendar;
            const direction = parseInt(this.dataset.direction);
            changeMonth(calendarId, direction);
        });
    });
    
    // Form submission handler
    const leaveForm = document.getElementById('leaveForm');
    if (leaveForm) {
        leaveForm.addEventListener('submit', function(e) {
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');
            
            // Convert display format to ISO format for submission
            if (startInput.value) {
                const startISO = startInput.getAttribute('data-value');
                if (startISO) {
                    const hiddenStart = document.createElement('input');
                    hiddenStart.type = 'hidden';
                    hiddenStart.name = 'start_date';
                    hiddenStart.value = startISO;
                    this.appendChild(hiddenStart);
                    startInput.removeAttribute('name');
                }
            }
            
            if (endInput.value) {
                const endISO = endInput.getAttribute('data-value');
                if (endISO) {
                    const hiddenEnd = document.createElement('input');
                    hiddenEnd.type = 'hidden';
                    hiddenEnd.name = 'end_date';
                    hiddenEnd.value = endISO;
                    this.appendChild(hiddenEnd);
                    endInput.removeAttribute('name');
                }
            }
        });
    }
});

function initCalendar(calendarId, inputId) {
    calendars[calendarId] = {
        inputId: inputId,
        month: currentMonth,
        year: currentYear,
        selectedDate: null
    };
    renderCalendar(calendarId);
}

function renderCalendar(calendarId) {
    const cal = calendars[calendarId];
    const calendarEl = document.getElementById(calendarId);
    const daysContainer = calendarEl.querySelector('.calendar-days');
    const titleEl = calendarEl.querySelector('.calendar-title');
    
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'];
    
    titleEl.textContent = `${monthNames[cal.month]} ${cal.year}`;
    
    const firstDay = new Date(cal.year, cal.month, 1).getDay();
    const daysInMonth = new Date(cal.year, cal.month + 1, 0).getDate();
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    daysContainer.innerHTML = '';
    
    // Empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        daysContainer.innerHTML += '<span class="calendar-day empty"></span>';
    }
    
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(cal.year, cal.month, day);
        date.setHours(0, 0, 0, 0);
        const isPast = date < today;
        const isToday = date.getTime() === today.getTime();
        
        let classes = 'calendar-day';
        if (isPast) classes += ' disabled';
        if (isToday) classes += ' today';
        
        const daySpan = document.createElement('span');
        daySpan.className = classes;
        daySpan.textContent = day;
        
        if (!isPast) {
            daySpan.addEventListener('click', function() {
                selectDate(calendarId, day);
            });
        }
        
        daysContainer.appendChild(daySpan);
    }
}

function changeMonth(calendarId, direction) {
    const cal = calendars[calendarId];
    cal.month += direction;
    
    if (cal.month > 11) {
        cal.month = 0;
        cal.year++;
    } else if (cal.month < 0) {
        cal.month = 11;
        cal.year--;
    }
    
    renderCalendar(calendarId);
}

function selectDate(calendarId, day) {
    const cal = calendars[calendarId];
    const input = document.getElementById(cal.inputId);
    const date = new Date(cal.year, cal.month, day);
    
    const formattedDate = `${String(day).padStart(2, '0')}/${String(cal.month + 1).padStart(2, '0')}/${cal.year}`;
    input.value = formattedDate;
    
    // Store ISO format in a hidden field for form submission
    const isoDate = date.toISOString().split('T')[0];
    input.setAttribute('data-value', isoDate);
    
    document.getElementById(calendarId).classList.remove('active');
}

// Delete confirmation
function deleteLeave(id) {
    if (confirm('Are you sure you want to delete this leave request?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/RedForce/caretaker/deleteLeave/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}
