<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<style>
    :root {
        --bg: #f0f2f5;
        --card: #fff;
        --muted: #606770;
        --accent: #a40000;
        --shadow: 0 6px 18px rgba(20,20,40,0.06);
        --radius: 12px;
    }

    .shell {
        padding: 24px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #333;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 24px;
        transition: all 0.2s;
    }

    .back-btn:hover {
        background: #f5f5f5;
        transform: translateX(-4px);
    }

    .site-cover {
        width: 100%;
        height: 280px;
        background-size: cover;
        background-position: center;
        border-radius: var(--radius);
        margin-bottom: 24px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .site-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-card {
        background: white;
        border-radius: var(--radius);
        padding: 28px;
        box-shadow: var(--shadow);
        margin-bottom: 24px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .info-card h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f0f0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ffe0e0 0%, #ffd0d0 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon .material-symbols-outlined {
        color: var(--accent);
        font-size: 22px;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 16px;
        color: #1a1a1a;
        font-weight: 600;
    }

    .schedule-section {
        background: #ffffff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid rgba(0,0,0,0.05);
        padding: 24px;
    }

    .schedule-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .calendar-panel {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .schedule-title {
        margin: 0 0 20px 0;
        font-size: 24px;
        font-weight: 700;
        color: #a40000;
    }

    .calendar-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 15px;
    }

    .month-label {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        text-align: center;
    }

    .nav-btn {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 999px;
        background: var(--accent);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .nav-btn:hover {
        background: #b50000;
        transform: scale(1.05);
    }

    .nav-btn .material-symbols-outlined {
        font-size: 20px;
    }

    .weekdays,
    .calendar-body {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .weekdays {
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .weekday {
        text-align: center;
        font-weight: 600;
        font-size: 14px;
        color: #666;
        padding: 15px 10px;
        border-right: 1px solid #e9ecef;
    }

    .weekday:last-child {
        border-right: none;
    }

    .calendar-day {
        min-height: 80px;
        background: #ffffff;
        border: 0;
        border-right: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        border-radius: 0;
        padding: 8px;
        font-size: 14px;
        font-weight: 400;
        color: #2f2f2f;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        position: relative;
    }

    .calendar-day:hover {
        background: #f8fbff;
        transform: scale(1.02);
    }

    .calendar-day.other-month {
        color: #c3c7cc;
        background: #f8f9fa;
        font-weight: 400;
    }

    .calendar-day.other-month:hover {
        background: #f8f9fa;
        transform: scale(1);
    }

    .calendar-day.today {
        background: #e8f4ff;
        box-shadow: inset 0 0 0 2px #2196f3;
        font-weight: 700;
    }

    .calendar-day.selected {
        background: #cfe3f5;
        box-shadow: inset 0 0 0 2px #2196f3;
    }

    .calendar-day.service-period {
        background: #e8f5e9 !important;
        box-shadow: inset 0 0 0 1px #81c784;
    }

    .calendar-day.service-period:hover {
        background: #dff1e1 !important;
    }

    .calendar-day.service-period.selected {
        background: #c8e6c9 !important;
        box-shadow: inset 0 0 0 2px #2e7d32;
    }

    .calendar-day.service-period .day-number {
        color: #1b5e20;
        font-weight: 700;
    }

    .day-number {
        font-weight: 600;
        font-size: 14px;
        color: inherit;
    }

    .schedule-panel {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .schedule-panel h3 {
        margin: 0;
        color: #333;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .selected-date {
        font-size: 14px;
        color: #666;
        margin: 0;
        margin-bottom: 16px;
    }

    .schedule-separator {
        border: 0;
        border-top: 1px solid #f0f0f0;
        margin: 0 0 18px;
    }

    .schedule-empty {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .schedule-empty .material-symbols-outlined {
        font-size: 48px;
        color: #ddd;
        display: block;
        margin-bottom: 12px;
    }

    .group-block + .group-block {
        margin-top: 16px;
    }

    .group-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .duty-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .duty-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #eceff3;
        border-radius: 10px;
        padding: 10px 12px;
    }

    .duty-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex: 1;
    }

    .duty-actions {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .rating-mini-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        color: #8a2d00;
        background: #fff2df;
        border: 1px solid #ffd7ad;
    }

    .rate-btn {
        border: 1px solid #b20000;
        background: #fff;
        color: #a40000;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .rate-btn:hover {
        background: #fff4f4;
    }

    .duty-name {
        font-weight: 600;
        color: #111827;
        font-size: 16px;
    }

    .duty-meta {
        font-size: 13px;
        color: #6b7280;
    }

    .shift-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 82px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .shift-day {
        background: #e7f5e7;
        color: #2e7d32;
    }

    .shift-night {
        background: #e3f2fd;
        color: #1565c0;
    }

    .shift-full {
        background: #f3e5f5;
        color: #6a1b9a;
    }

    .rating-alert {
        margin-bottom: 16px;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
    }

    .rating-alert.success {
        background: #e8f5e9;
        color: #1b5e20;
        border: 1px solid #c8e6c9;
    }

    .rating-alert.error {
        background: #ffebee;
        color: #b71c1c;
        border: 1px solid #ffcdd2;
    }

    .rating-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1200;
    }

    .rating-modal-overlay.active {
        display: flex;
    }

    .rating-modal {
        width: 92%;
        max-width: 520px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0dede;
        box-shadow: 0 16px 35px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .rating-modal-header {
        background: linear-gradient(135deg, #8f0000 0%, #b40000 100%);
        color: #fff;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .rating-modal-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .rating-close-btn {
        border: 0;
        background: transparent;
        color: #fff;
        cursor: pointer;
    }

    .rating-modal-body {
        padding: 16px;
    }

    .rating-meta {
        margin: 0 0 12px;
        color: #6b7280;
        font-size: 13px;
    }

    .rating-stars {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
    }

    .rating-stars label {
        cursor: pointer;
        font-size: 26px;
        color: #d1d5db;
        transition: color 0.15s;
    }

    .rating-stars input {
        display: none;
    }

    .rating-stars label.active {
        color: #ffb300;
    }

    .rating-textarea {
        width: 100%;
        min-height: 96px;
        border: 1px solid #d7dbe3;
        border-radius: 10px;
        padding: 10px;
        font-size: 14px;
        resize: vertical;
        font-family: inherit;
    }

    .rating-modal-actions {
        margin-top: 14px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .rating-primary,
    .rating-delete {
        border: 0;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .rating-primary {
        background: #a40000;
        color: #fff;
    }

    .rating-delete {
        background: #ffe9e9;
        color: #b00000;
    }

    @media (max-width: 1400px) {
        .weekday {
            font-size: 12px;
            padding: 10px 5px;
        }

        .calendar-day {
            min-height: 60px;
        }

        .month-label {
            font-size: 16px;
        }

        .schedule-panel h3 {
            font-size: 16px;
        }

        .selected-date {
            font-size: 13px;
        }

        .group-title {
            font-size: 15px;
        }
    }

    @media (max-width: 992px) {
        .schedule-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="shell" role="main">
    <a href="<?php echo URL_ROOT; ?>/client/sites" class="back-btn">
        <span class="material-symbols-outlined">arrow_back</span>
        Back to Sites
    </a>

    <?php if (!empty($data['rating_success'])): ?>
        <div class="rating-alert success"><?php echo htmlspecialchars($data['rating_success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($data['rating_error'])): ?>
        <div class="rating-alert error"><?php echo htmlspecialchars($data['rating_error']); ?></div>
    <?php endif; ?>

    <div class="site-cover">
        <?php if(!empty($data['site']->image)): ?>
            <img src="<?php echo URL_ROOT; ?>/uploads/siteImages/<?php echo $data['site']->image; ?>"
                 alt="<?php echo htmlspecialchars($data['site']->site_name); ?>"
                 class="site-cover-img">
        <?php else: ?>
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%); display: flex; align-items: center; justify-content: center;">
                <span class="material-symbols-outlined" style="font-size: 80px; color: #ccc;">location_city</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="info-card">
        <h2><?php echo htmlspecialchars($data['site']->site_name); ?></h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <div class="info-content">
                    <div class="info-label">Address</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['site']->address); ?></div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">location_city</span>
                </div>
                <div class="info-content">
                    <div class="info-label">City & District</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($data['site']->city); ?>
                        <?php echo !empty($data['site']->district) ? ', ' . htmlspecialchars($data['site']->district) : ''; ?>
                    </div>
                </div>
            </div>

            <?php if(!empty($data['site']->phone_number)): ?>
            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">call</span>
                </div>
                <div class="info-content">
                    <div class="info-label">Contact Number</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['site']->phone_number); ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($data['site']->supervisor_name)): ?>
            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">supervisor_account</span>
                </div>
                <div class="info-content">
                    <div class="info-label">Supervisor</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['site']->supervisor_name); ?></div>
                    <?php if(!empty($data['site']->supervisor_phone)): ?>
                        <div style="font-size: 13px; color: #666; margin-top: 4px;">
                            <?php echo htmlspecialchars($data['site']->supervisor_phone); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">event</span>
                </div>
                <div class="info-content">
                    <div class="info-label">Created Date</div>
                    <div class="info-value"><?php echo date('M d, Y', strtotime($data['site']->created_at)); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="schedule-section">
        <h2 class="schedule-title">Site Schedule Calendar</h2>
        <div class="schedule-grid">
            <div class="calendar-panel">
                <div class="calendar-toolbar">
                    <button class="nav-btn" id="prevMonth" type="button" aria-label="Previous month">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <div class="month-label" id="currentMonthYear">April 2026</div>
                    <button class="nav-btn" id="nextMonth" type="button" aria-label="Next month">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>

                <div class="weekdays">
                    <div class="weekday">MON</div>
                    <div class="weekday">TUE</div>
                    <div class="weekday">WED</div>
                    <div class="weekday">THU</div>
                    <div class="weekday">FRI</div>
                    <div class="weekday">SAT</div>
                    <div class="weekday">SUN</div>
                </div>

                <div class="calendar-body" id="calendarBody"></div>
            </div>

            <div class="schedule-panel">
                <h3>Schedule Details</h3>
                <div class="selected-date" id="selectedDate">Select a date</div>
                <hr class="schedule-separator">

                <div id="shiftContent" class="schedule-empty">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <p>Click on a date to view duty staff for that day</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rating-modal-overlay" id="officerRatingModal" aria-hidden="true">
    <div class="rating-modal">
        <div class="rating-modal-header">
            <h4>Rate Premise Officer</h4>
            <button type="button" class="rating-close-btn" id="closeOfficerRatingModal">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="rating-modal-body">
            <p class="rating-meta" id="ratingOfficerMeta">Officer</p>

            <form method="POST" action="<?php echo URL_ROOT; ?>/client/saveOfficerRating" id="saveOfficerRatingForm">
                <input type="hidden" name="site_id" value="<?php echo (int)$data['site']->id; ?>">
                <input type="hidden" name="officer_user_id" id="ratingOfficerId" value="">
                <input type="hidden" name="rating_date" id="ratingDateInput" value="">
                <input type="hidden" name="rating_value" id="ratingValueInput" value="0">

                <div class="rating-stars" id="ratingStarsWrap">
                    <label data-value="1">★</label>
                    <label data-value="2">★</label>
                    <label data-value="3">★</label>
                    <label data-value="4">★</label>
                    <label data-value="5">★</label>
                </div>

                <textarea class="rating-textarea" name="description" id="ratingDescription" maxlength="600" placeholder="Write a short note about this officer's performance (optional)"></textarea>

                <div class="rating-modal-actions">
                    <button type="submit" class="rating-primary">Save Rating</button>
                </div>
            </form>

            <form method="POST" action="<?php echo URL_ROOT; ?>/client/deleteOfficerRating" id="deleteOfficerRatingForm" style="display:none; margin-top: 8px; text-align: right;">
                <input type="hidden" name="site_id" value="<?php echo (int)$data['site']->id; ?>">
                <input type="hidden" name="officer_user_id" id="deleteRatingOfficerId" value="">
                <input type="hidden" name="rating_date" id="deleteRatingDateInput" value="">
                <button type="submit" class="rating-delete">Delete Rating</button>
            </form>
        </div>
    </div>
</div>

<script>
    const assignedOfficers = <?php echo json_encode($data['assigned_officers'] ?? []); ?>;
    const assignedSupervisors = <?php echo json_encode($data['assigned_supervisors'] ?? []); ?>;
    const clientOfficerRatings = <?php echo json_encode($data['client_officer_ratings'] ?? []); ?>;
    const serviceStartDate = <?php echo json_encode($data['site']->service_start_date ?? null); ?>;
    const serviceEndDate = <?php echo json_encode($data['site']->service_end_date ?? null); ?>;

    (function initializeSiteScheduleCalendar() {
        const calendarBody = document.getElementById('calendarBody');
        const currentMonthYearSpan = document.getElementById('currentMonthYear');
        const selectedDateSpan = document.getElementById('selectedDate');
        const shiftContent = document.getElementById('shiftContent');
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');

        if (!calendarBody || !currentMonthYearSpan || !prevMonthBtn || !nextMonthBtn || !shiftContent) {
            return;
        }

        const parsedServiceStart = parseDateOnly(serviceStartDate);
        let currentDate = parsedServiceStart || new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        let selectedKey = null;
        const ratingModal = document.getElementById('officerRatingModal');
        const closeRatingModalBtn = document.getElementById('closeOfficerRatingModal');
        const ratingOfficerMeta = document.getElementById('ratingOfficerMeta');
        const ratingOfficerIdInput = document.getElementById('ratingOfficerId');
        const ratingDateInput = document.getElementById('ratingDateInput');
        const deleteRatingOfficerId = document.getElementById('deleteRatingOfficerId');
        const deleteRatingDateInput = document.getElementById('deleteRatingDateInput');
        const deleteForm = document.getElementById('deleteOfficerRatingForm');
        const ratingValueInput = document.getElementById('ratingValueInput');
        const ratingDescriptionInput = document.getElementById('ratingDescription');
        const ratingStarLabels = Array.from(document.querySelectorAll('#ratingStarsWrap label'));
        const saveOfficerRatingForm = document.getElementById('saveOfficerRatingForm');
        const ratingMap = {};

        clientOfficerRatings.forEach((row) => {
            const key = String(row.officer_user_id) + '_' + String(row.rating_date);
            ratingMap[key] = row;
        });

        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        function normalizeShift(shiftType) {
            const shift = String(shiftType || 'Full Time').trim().toLowerCase();
            if (shift === 'day') {
                return { label: 'Day Shift', className: 'shift-day' };
            }
            if (shift === 'night') {
                return { label: 'Night Shift', className: 'shift-night' };
            }
            return { label: 'Full Time', className: 'shift-full' };
        }

        function dayCell(day, month, year, isOtherMonth, isToday) {
            const el = document.createElement('div');
            el.className = 'calendar-day' + (isOtherMonth ? ' other-month' : '') + (isToday ? ' today' : '');
            el.innerHTML = '<span class="day-number">' + String(day) + '</span>';
            el.dataset.day = String(day);
            el.dataset.month = String(month);
            el.dataset.year = String(year);
            return el;
        }

        function parseDateOnly(dateStr) {
            if (!dateStr) {
                return null;
            }
            const parts = String(dateStr).split('-');
            if (parts.length !== 3) {
                return null;
            }
            return new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
        }

        function isDateWithinRange(targetKey, startStr, endStr) {
            const target = parseDateOnly(targetKey);
            const start = parseDateOnly(startStr);
            const end = parseDateOnly(endStr);

            if (!target || !start) {
                return false;
            }

            if (!end) {
                return target >= start;
            }

            return target >= start && target <= end;
        }

        function isServicePeriodDate(dateKey) {
            return isDateWithinRange(dateKey, serviceStartDate, serviceEndDate);
        }

        function filterOnDutyByDate(list, dateKey) {
            if (!Array.isArray(list) || list.length === 0) {
                return [];
            }

            return list.filter((person) => {
                return isDateWithinRange(dateKey, person.assignment_start, person.assignment_end);
            });
        }

        function renderCalendar(month, year) {
            calendarBody.innerHTML = '';
            currentMonthYearSpan.textContent = monthNames[month] + ' ' + year;

            const firstDay = new Date(year, month, 1);
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const mondayStart = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;

            const prevMonth = month === 0 ? 11 : month - 1;
            const prevYear = month === 0 ? year - 1 : year;
            const prevMonthDays = new Date(prevYear, prevMonth + 1, 0).getDate();

            const today = new Date();
            const todayDay = today.getDate();
            const todayMonth = today.getMonth();
            const todayYear = today.getFullYear();

            for (let i = mondayStart - 1; i >= 0; i--) {
                const day = prevMonthDays - i;
                calendarBody.appendChild(dayCell(day, prevMonth, prevYear, true, false));
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = day === todayDay && month === todayMonth && year === todayYear;
                const el = dayCell(day, month, year, false, isToday);
                const dateKey = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');

                if (isServicePeriodDate(dateKey)) {
                    el.classList.add('service-period');
                }

                el.addEventListener('click', () => {
                    selectedKey = dateKey;
                    renderSelectedState();
                    renderScheduleDetails(day, month, year, dateKey);
                });

                calendarBody.appendChild(el);
            }

            const totalCells = calendarBody.children.length;
            const remaining = 42 - totalCells;
            const nextMonth = month === 11 ? 0 : month + 1;
            const nextYear = month === 11 ? year + 1 : year;

            for (let day = 1; day <= remaining; day++) {
                calendarBody.appendChild(dayCell(day, nextMonth, nextYear, true, false));
            }

            renderSelectedState();
        }

        function renderSelectedState() {
            const allDays = calendarBody.querySelectorAll('.calendar-day');
            allDays.forEach((el) => {
                const key =
                    el.dataset.year + '-' +
                    String(Number(el.dataset.month) + 1).padStart(2, '0') + '-' +
                    String(el.dataset.day).padStart(2, '0');
                if (selectedKey && key === selectedKey) {
                    el.classList.add('selected');
                } else {
                    el.classList.remove('selected');
                }
            });
        }

        function renderDutyGroup(title, icon, list, allowRating, dateKey) {
            if (!Array.isArray(list) || list.length === 0) {
                return '';
            }

            const items = list.map((person) => {
                const shift = normalizeShift(person.shift_type);
                const rankText = person.rank || person.officer_rank || person.role || 'N/A';
                const personId = Number(person.id || person.user_id || 0);
                const ratingKey = String(personId) + '_' + String(dateKey);
                const existingRating = ratingMap[ratingKey] || null;
                const ratingBadge = existingRating
                    ? `<span class="rating-mini-badge">${escapeHtml(String(existingRating.rating_value))}/5</span>`
                    : '';
                const rateButton = allowRating && personId > 0
                    ? `<button type="button" class="rate-btn"
                           data-officer-id="${personId}"
                           data-officer-name="${escapeHtml(person.name || 'Unnamed')}"
                           data-officer-rank="${escapeHtml(String(rankText))}"
                           data-rating-date="${escapeHtml(String(dateKey))}">
                          ${existingRating ? 'Edit' : 'Rate'}
                       </button>`
                    : '';
                return `
                    <div class="duty-item">
                        <div class="duty-main">
                            <div>
                                <div class="duty-name">${escapeHtml(person.name || 'Unnamed')}</div>
                                <div class="duty-meta">Rank: ${escapeHtml(String(rankText))}</div>
                            </div>
                            <div class="duty-actions">${ratingBadge}${rateButton}</div>
                        </div>
                        <span class="shift-badge ${shift.className}">${shift.label}</span>
                    </div>
                `;
            }).join('');

            return `
                <div class="group-block">
                    <div class="group-title">
                        <span class="material-symbols-outlined">${icon}</span>
                        <span>${title} (${list.length})</span>
                    </div>
                    <div class="duty-list">${items}</div>
                </div>
            `;
        }

        function renderScheduleDetails(day, month, year, dateKey) {
            selectedDateSpan.textContent = monthNames[month] + ' ' + day + ', ' + year;

            const officersOnDuty = filterOnDutyByDate(assignedOfficers, dateKey);
            const supervisorsOnDuty = filterOnDutyByDate(assignedSupervisors, dateKey);

            const officerGroup = renderDutyGroup('Premise Officers On Duty', 'badge', officersOnDuty, true, dateKey);
            const supervisorGroup = renderDutyGroup('Supervisors On Duty', 'supervisor_account', supervisorsOnDuty, false, dateKey);

            if (!officerGroup && !supervisorGroup) {
                shiftContent.className = 'schedule-empty';
                shiftContent.innerHTML = `
                    <span class="material-symbols-outlined">event_busy</span>
                    <p>No duty assignments available for ${escapeHtml(dateKey)}</p>
                `;
                return;
            }

            shiftContent.className = '';
            shiftContent.innerHTML = officerGroup + supervisorGroup;
            bindRateButtons();
        }

        function setStarSelection(value) {
            const numeric = Number(value) || 0;
            ratingValueInput.value = String(numeric);
            ratingStarLabels.forEach((label) => {
                const val = Number(label.dataset.value || 0);
                label.classList.toggle('active', val <= numeric);
            });
        }

        function openRatingModal(officerId, officerName, officerRank, ratingDate) {
            const ratingKey = String(officerId) + '_' + String(ratingDate);
            const existing = ratingMap[ratingKey] || null;

            ratingOfficerMeta.textContent = officerName + ' (' + officerRank + ') - ' + ratingDate;
            ratingOfficerIdInput.value = String(officerId);
            ratingDateInput.value = String(ratingDate);
            deleteRatingOfficerId.value = String(officerId);
            deleteRatingDateInput.value = String(ratingDate);

            if (existing) {
                setStarSelection(existing.rating_value);
                ratingDescriptionInput.value = existing.description || '';
                deleteForm.style.display = 'block';
            } else {
                setStarSelection(0);
                ratingDescriptionInput.value = '';
                deleteForm.style.display = 'none';
            }

            ratingModal.classList.add('active');
            ratingModal.setAttribute('aria-hidden', 'false');
        }

        function closeRatingModal() {
            ratingModal.classList.remove('active');
            ratingModal.setAttribute('aria-hidden', 'true');
        }

        function bindRateButtons() {
            shiftContent.querySelectorAll('.rate-btn').forEach((btn) => {
                btn.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const officerId = Number(btn.dataset.officerId || 0);
                    const officerName = String(btn.dataset.officerName || 'Officer');
                    const officerRank = String(btn.dataset.officerRank || 'N/A');
                    const ratingDate = String(btn.dataset.ratingDate || '');
                    if (!officerId || !ratingDate) {
                        return;
                    }
                    openRatingModal(officerId, officerName, officerRank, ratingDate);
                });
            });
        }

        ratingStarLabels.forEach((label) => {
            label.addEventListener('click', () => {
                setStarSelection(Number(label.dataset.value || 0));
            });
        });

        if (closeRatingModalBtn) {
            closeRatingModalBtn.addEventListener('click', closeRatingModal);
        }

        if (ratingModal) {
            ratingModal.addEventListener('click', (event) => {
                if (event.target === ratingModal) {
                    closeRatingModal();
                }
            });
        }

        if (saveOfficerRatingForm) {
            saveOfficerRatingForm.addEventListener('submit', (event) => {
                if (Number(ratingValueInput.value || 0) < 1) {
                    event.preventDefault();
                    alert('Please select a rating between 1 and 5.');
                }
            });
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        prevMonthBtn.addEventListener('click', () => {
            currentMonth -= 1;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear -= 1;
            }
            renderCalendar(currentMonth, currentYear);
        });

        nextMonthBtn.addEventListener('click', () => {
            currentMonth += 1;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear += 1;
            }
            renderCalendar(currentMonth, currentYear);
        });

        renderCalendar(currentMonth, currentYear);

        // Show service start date by default when available, otherwise today.
        const defaultDateObj = parsedServiceStart || new Date();
        const defaultKey = defaultDateObj.getFullYear() + '-' + String(defaultDateObj.getMonth() + 1).padStart(2, '0') + '-' + String(defaultDateObj.getDate()).padStart(2, '0');
        selectedKey = defaultKey;
        renderSelectedState();
        renderScheduleDetails(defaultDateObj.getDate(), defaultDateObj.getMonth(), defaultDateObj.getFullYear(), defaultKey);
    })();
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>