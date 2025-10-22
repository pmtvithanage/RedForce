// DOM Elements
const leaveForm = document.getElementById('leaveForm');
const fileInput = document.getElementById('fileInput');
const attachFileBtn = document.getElementById('attachFile');
const fileName = document.getElementById('fileName');
const leaveHistoryBody = document.getElementById('leaveHistoryBody');
const startDateInput = document.getElementById('startDate');
const endDateInput = document.getElementById('endDate');

// Calendar state
let currentDate = new Date();
let selectedStartDate = null;
let selectedEndDate = null;

// Initialize Inline Calendars
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    initializeCalendar('startCalendar', today);
    initializeCalendar('endCalendar', today);
    
    // Add click event listeners to date input fields
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    
    if (startDateInput) {
        startDateInput.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleCalendar('startCalendar');
        });
        
        startDateInput.addEventListener('change', function() {
            clearFieldError(this);
        });
        
        startDateInput.removeAttribute('required');
        clearFieldError(startDateInput);
    }
    
    if (endDateInput) {
        endDateInput.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleCalendar('endCalendar');
        });
        
        endDateInput.addEventListener('change', function() {
            clearFieldError(this);
        });
        
        endDateInput.removeAttribute('required');
        clearFieldError(endDateInput);
    }
    
    // Close calendars when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.date-input-wrapper')) {
            closeAllCalendars();
        }
    });
    
    // Remove required attributes from all form fields
    const formFields = document.querySelectorAll('#leaveForm [required]');
    formFields.forEach(field => {
        field.removeAttribute('required');
    });
});

// Calendar Functions
function initializeCalendar(calendarId, date) {
    const calendar = document.getElementById(calendarId);
    const titleElement = document.getElementById(calendarId + 'Title');
    const daysElement = document.getElementById(calendarId + 'Days');
    
    renderCalendar(calendarId, date);
}

function renderCalendar(calendarId, date) {
    const titleElement = document.getElementById(calendarId + 'Title');
    const daysElement = document.getElementById(calendarId + 'Days');
    
    const year = date.getFullYear();
    const month = date.getMonth();
    
    // Update title
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    titleElement.textContent = `${monthNames[month]} ${year}`;
    
    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    
    let calendarHTML = '';
    
    // Generate calendar days
    for (let i = 0; i < 42; i++) {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + i);
        
        const dayNumber = currentDate.getDate();
        const isCurrentMonth = currentDate.getMonth() === month;
        const isToday = isTodayDate(currentDate);
        const isSelected = isSelectedDate(currentDate, calendarId);
        const isDisabled = isDisabledDate(currentDate, calendarId);
        
        let className = 'calendar-day';
        if (!isCurrentMonth) className += ' other-month';
        if (isToday) className += ' today';
        if (isSelected) className += ' selected';
        if (isDisabled) className += ' disabled';
        
        calendarHTML += `<span class="${className}" onclick="selectDate('${calendarId}', '${currentDate.toISOString()}')">${dayNumber}</span>`;
    }
    
    daysElement.innerHTML = calendarHTML;
}

function isTodayDate(date) {
    const today = new Date();
    return date.getDate() === today.getDate() && 
           date.getMonth() === today.getMonth() && 
           date.getFullYear() === today.getFullYear();
}

function isSelectedDate(date, calendarId) {
    if (calendarId === 'startCalendar') {
        return selectedStartDate && isSameDate(date, selectedStartDate);
    } else if (calendarId === 'endCalendar') {
        return selectedEndDate && isSameDate(date, selectedEndDate);
    }
    return false;
}

function isDisabledDate(date, calendarId) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (calendarId === 'startCalendar') {
        return date < today;
    } else if (calendarId === 'endCalendar') {
        return date < today || (selectedStartDate && date < selectedStartDate);
    }
    return false;
}

function isSameDate(date1, date2) {
    return date1.getDate() === date2.getDate() && 
           date1.getMonth() === date2.getMonth() && 
           date1.getFullYear() === date2.getFullYear();
}

function selectDate(calendarId, dateString) {
    const date = new Date(dateString);
    
    if (calendarId === 'startCalendar') {
        selectedStartDate = date;
        const startInput = document.getElementById('startDate');
        if (startInput) {
            startInput.value = formatDateForInput(date);
            clearFieldError(startInput);
            startInput.dispatchEvent(new Event('change'));
        }
        closeAllCalendars();
        
        // Update end calendar if start date is after end date
        if (selectedEndDate && date > selectedEndDate) {
            selectedEndDate = null;
            const endInput = document.getElementById('endDate');
            if (endInput) {
                endInput.value = '';
            }
        }
        
        // Re-render both calendars
        renderCalendar('startCalendar', date);
        if (selectedEndDate) {
            renderCalendar('endCalendar', selectedEndDate);
        } else {
            renderCalendar('endCalendar', date);
        }
        
    } else if (calendarId === 'endCalendar') {
        if (selectedStartDate && date < selectedStartDate) {
            showNotification('End date cannot be before start date', 'error');
            return;
        }
        selectedEndDate = date;
        const endInput = document.getElementById('endDate');
        if (endInput) {
            endInput.value = formatDateForInput(date);
            clearFieldError(endInput);
            endInput.dispatchEvent(new Event('change'));
        }
        closeAllCalendars();
        
        renderCalendar('endCalendar', date);
    }
}

function formatDateForInput(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function changeMonth(calendarId, direction) {
    const titleElement = document.getElementById(calendarId + 'Title');
    const currentTitle = titleElement.textContent;
    const [monthName, year] = currentTitle.split(' ');
    
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    
    let currentMonthIndex = monthNames.indexOf(monthName);
    let currentYear = parseInt(year);
    
    currentMonthIndex += direction;
    
    if (currentMonthIndex < 0) {
        currentMonthIndex = 11;
        currentYear--;
    } else if (currentMonthIndex > 11) {
        currentMonthIndex = 0;
        currentYear++;
    }
    
    const newDate = new Date(currentYear, currentMonthIndex, 1);
    renderCalendar(calendarId, newDate);
}

function toggleCalendar(calendarId) {
    const calendar = document.getElementById(calendarId);
    const isActive = calendar.classList.contains('active');
    
    closeAllCalendars();
    
    if (!isActive) {
        calendar.classList.add('active');
    }
}

function closeAllCalendars() {
    const calendars = document.querySelectorAll('.inline-calendar');
    calendars.forEach(calendar => {
        calendar.classList.remove('active');
    });
}

// File Upload Functionality
if (attachFileBtn && fileInput && fileName) {
    attachFileBtn.addEventListener('click', () => {
        fileInput.click();
    });

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

// Form Submission
if (leaveForm) {
    leaveForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(leaveForm);
        const leaveData = {
            leaveType: formData.get('leave_type'),
            leaveReason: formData.get('reason'),
            startDate: formData.get('start_date'),
            endDate: formData.get('end_date'),
            proof: fileInput.files[0] ? fileInput.files[0].name : null,
            status: 'Pending',
            submittedAt: new Date().toLocaleDateString('en-GB')
        };
        
        // Validate form
        if (!leaveData.leaveType || !leaveData.leaveReason || !leaveData.startDate || !leaveData.endDate) {
            showNotification('Please fill in all required fields', 'error');
            return;
        }
        
        // Additional validation for date format
        const dateRegex = /^\d{2}\/\d{2}\/\d{4}$/;
        if (!dateRegex.test(leaveData.startDate) || !dateRegex.test(leaveData.endDate)) {
            showNotification('Please select valid dates using the calendar', 'error');
            return;
        }
        
        // Validate that end date is not before start date
        const startDate = new Date(leaveData.startDate.split('/').reverse().join('-'));
        const endDate = new Date(leaveData.endDate.split('/').reverse().join('-'));
        
        if (endDate < startDate) {
            showNotification('End date cannot be before start date', 'error');
            return;
        }
        
        // If validation passes, submit the form to the server
        leaveForm.submit();
    });
}

// Notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: ${getNotificationColor(type)};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
    `;
    
    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Add to page
    document.body.appendChild(notification);
    
    // Close button functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.remove();
    });
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Get notification color based on type
function getNotificationColor(type) {
    switch (type) {
        case 'success':
            return '#28a745';
        case 'error':
            return '#dc3545';
        case 'warning':
            return '#ffc107';
        default:
            return '#17a2b8';
    }
}

// Field validation
function validateField(field) {
    const value = field.value.trim();
    
    if (field.id === 'startDate' || field.id === 'endDate') {
        if (!value) {
            showFieldError(field, 'Please select a date');
            return false;
        }
        const dateRegex = /^\d{2}\/\d{2}\/\d{4}$/;
        if (!dateRegex.test(value)) {
            showFieldError(field, 'Please select a valid date');
            return false;
        }
    } else if (!value) {
        showFieldError(field, 'This field is required');
        return false;
    }
    
    if (field.hasAttribute('readonly')) {
        return true;
    }
    
    clearFieldError(field);
    return true;
}

// Show field error
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = '#dc3545';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    `;
    
    field.parentNode.appendChild(errorDiv);
}

// Clear field error
function clearFieldError(field) {
    field.style.borderColor = '#ddd';
    field.style.backgroundColor = '';
    
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
    
    field.classList.remove('error');
}

// Delete leave request function
function deleteLeave(id) {
    if (confirm('Are you sure you want to delete this leave request?')) {
        const baseUrl = window.location.origin;
        const path = window.location.pathname.split('/')[1];
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/${path}/mobilerider/deleteLeave/${id}`;
        document.body.appendChild(form);
        form.submit();
    }
}

// Form validation on input
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.leave-form input, .leave-form textarea, .leave-form select');
    
    inputs.forEach(input => {
        input.addEventListener('blur', () => {
            validateField(input);
        });
        
        input.addEventListener('input', () => {
            clearFieldError(input);
        });
        
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', () => {
                clearFieldError(input);
            });
        }
    });
});
