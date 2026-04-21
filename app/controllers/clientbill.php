<?php
// Personal reference file: view-only form snippets.
// This file is not a real controller.
?>

<!-- Contact Number -->
<div class="field">
    <div class="field-label">Contact Number: <span style="color: red;">*</span></div>
    <input
        class="field-input"
        type="tel"
        id="contact_number"
        name="contact_number"
        value="<?php echo isset($data['contact_number_value']) ? htmlspecialchars($data['contact_number_value']) : ''; ?>"
        placeholder="07XXXXXXXX"
        pattern="07[0-9]{8}"
        minlength="10"
        maxlength="10"
        required />
    <span class="form-input-error"><?php echo isset($data['contact_number_err']) ? $data['contact_number_err'] : ''; ?></span>
</div>

<!-- National ID -->
<div class="field">
    <div class="field-label">National ID: <span style="color: red;">*</span></div>
    <input
        class="field-input"
        type="text"
        id="national_id"
        name="national_id"
        value="<?php echo isset($data['national_id_value']) ? htmlspecialchars($data['national_id_value']) : ''; ?>"
        placeholder="200229303235"
        pattern="[0-9]{12}"
        minlength="12"
        maxlength="12"
        required />
    <span class="form-input-error"><?php echo isset($data['national_id_err']) ? $data['national_id_err'] : ''; ?></span>
</div>

<!-- Email -->
<div class="field">
    <div class="field-label">Email: <span style="color: red;">*</span></div>
    <input
        class="field-input"
        type="email"
        id="email"
        name="email"
        value="<?php echo isset($data['email_value']) ? htmlspecialchars($data['email_value']) : ''; ?>"
        placeholder="example@email.com"
        required />
    <span class="form-input-error"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
</div>

<!-- Start Date -->
<div class="field">
    <div class="field-label">Start Date: <span style="color: red;">*</span></div>
    <input
        class="field-input"
        type="date"
        id="start_date"
        name="start_date"
        value="<?php echo isset($data['start_date_value']) ? $data['start_date_value'] : ''; ?>"
        min="<?php echo date('Y-m-d'); ?>"
        required />
    <span class="form-input-error"><?php echo isset($data['start_date_err']) ? $data['start_date_err'] : ''; ?></span>
</div>

<!-- End Date -->
<div class="field">
    <div class="field-label">End Date: <span style="color: red;">*</span></div>
    <input
        class="field-input"
        type="date"
        id="end_date"
        name="end_date"
        value="<?php echo isset($data['end_date_value']) ? $data['end_date_value'] : ''; ?>"
        min="<?php echo date('Y-m-d'); ?>"
        required />
    <span class="form-input-error"><?php echo isset($data['end_date_err']) ? $data['end_date_err'] : ''; ?></span>
</div>

<!-- Price -->
<div class="field">
    <div class="field-label">Price (LKR): <span style="color: red;">*</span></div>
    <input
        class="field-input"
        type="number"
        id="price"
        name="price"
        value="<?php echo isset($data['price_value']) ? htmlspecialchars($data['price_value']) : ''; ?>"
        placeholder="1500.00"
        min="0"
        step="0.01"
        required />
    <span class="form-input-error"><?php echo isset($data['price_err']) ? $data['price_err'] : ''; ?></span>
</div>

<!-- Description -->
<div class="field">
    <div class="field-label">Description: <span style="color: red;">*</span></div>
    <textarea
        class="field-textarea"
        id="description"
        name="description"
        placeholder="Enter at least 10 characters"
        required><?php echo isset($data['description_value']) ? htmlspecialchars($data['description_value']) : ''; ?></textarea>
    <span class="form-input-error"><?php echo isset($data['description_err']) ? $data['description_err'] : ''; ?></span>
</div>

<!-- ======================================================= -->
<!-- Leave Request View Snippets - Contact Number (Reference) -->
<!-- ======================================================= -->

<!-- Leave Request Form: Contact Number field -->
<div class="field">
    <div class="field-label">Contact Number: <span style="color: red;">*</span></div>
    <input
        class="field-input"
        type="tel"
        id="contact_number"
        name="contact_number"
        value="<?php echo isset($data['contact_number_value']) ? htmlspecialchars($data['contact_number_value']) : ''; ?>"
        placeholder="07XXXXXXXX"
        pattern="07[0-9]{8}"
        minlength="10"
        maxlength="10"
        required />
    <span class="form-input-error"><?php echo isset($data['contact_number_err']) ? $data['contact_number_err'] : ''; ?></span>
</div>

<!-- Leave Request Table: add this header column -->
<th>Contact Number</th>

<!-- Leave Request Table: add this body cell in each row -->
<td><?php echo !empty($request->contact_number) ? htmlspecialchars($request->contact_number) : 'N/A'; ?></td>

<!-- Leave Request Table: add this header column -->
<th>ID Number</th>

<!-- Leave Request Table: add this body cell in each row -->
<td><?php echo !empty($request->national_id) ? htmlspecialchars($request->national_id) : 'N/A'; ?></td>

<!-- Leave Request Table: add this header column -->
<th>Email</th>

<!-- Leave Request Table: add this body cell in each row -->
<td><?php echo !empty($request->email) ? htmlspecialchars($request->email) : 'N/A'; ?></td>

<!-- ======================================================= -->
<!-- Leave Request Table Integration Example (Reference) -->
<!-- ======================================================= -->

<!-- Filters (search + status + month) -->
<div class="filter-controls">
    <div class="search-box">
        <span class="material-symbols-outlined">search</span>
        <input type="text" id="searchInput" placeholder="Search leave requests...">
    </div>

    <select id="statusFilter" class="filter-select">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
    </select>

    <select id="monthFilter" class="filter-select">
        <option value="">All Months</option>
    </select>
</div>

<!-- Table: keep column order aligned with row cells and JS indexes -->
<table id="leaveRequestsTable">
    <thead>
        <tr>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Duration</th>
            <th>Reason</th>
            <th>Contact Number</th>
            <th>ID Number</th>
            <th>Email</th>
            <th>Price</th>
            <th>Status</th>
            <th>Created Date</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach (($data['leaveRequests'] ?? []) as $request): ?>
            <tr>
                <td><?php echo htmlspecialchars($request->leave_type ?? 'N/A'); ?></td>
                <td><?php echo !empty($request->start_date) ? date('M d, Y', strtotime($request->start_date)) : 'N/A'; ?></td>
                <td><?php echo !empty($request->end_date) ? date('M d, Y', strtotime($request->end_date)) : 'N/A'; ?></td>
                <td><?php echo htmlspecialchars($request->duration ?? 'N/A'); ?></td>
                <td><?php echo !empty($request->reason) ? htmlspecialchars($request->reason) : 'N/A'; ?></td>
                <td><?php echo !empty($request->contact_number) ? htmlspecialchars($request->contact_number) : 'N/A'; ?></td>
                <td><?php echo !empty($request->national_id) ? htmlspecialchars($request->national_id) : 'N/A'; ?></td>
                <td><?php echo !empty($request->email) ? htmlspecialchars($request->email) : 'N/A'; ?></td>
                <td><?php echo isset($request->price) ? htmlspecialchars($request->price) : 'N/A'; ?></td>
                <td><?php echo !empty($request->status) ? htmlspecialchars($request->status) : 'N/A'; ?></td>
                <td><?php echo !empty($request->created_at) ? date('M d, Y', strtotime($request->created_at)) : 'N/A'; ?></td>
                <td><!-- action buttons --></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    // Column indexes must match the header order above.
    // 0 leaveType, 4 reason, 5 contact, 6 nationalId, 7 email, 8 price, 9 status, 10 createdDate
    function applyFilters() {
        const searchTerm = (document.getElementById('searchInput')?.value || '').toLowerCase();
        const statusFilter = (document.getElementById('statusFilter')?.value || '').toLowerCase();
        const monthFilter = document.getElementById('monthFilter')?.value || '';
        const table = document.getElementById('leaveRequestsTable');
        if (!table) return;

        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let row of rows) {
            const leaveType = row.cells[0].textContent.toLowerCase();
            const reason = row.cells[4].textContent.toLowerCase();
            const contactNumber = row.cells[5].textContent.toLowerCase();
            const nationalId = row.cells[6].textContent.toLowerCase();
            const email = row.cells[7].textContent.toLowerCase();
            const price = row.cells[8].textContent.toLowerCase();
            const status = row.cells[9].textContent.toLowerCase();
            const createdDateText = row.cells[10].textContent.trim();

            const createdDate = new Date(createdDateText);
            const rowMonth = !Number.isNaN(createdDate.getTime()) ?
                `${createdDate.getFullYear()}-${String(createdDate.getMonth() + 1).padStart(2, '0')}` :
                '';

            const matchesSearch = !searchTerm ||
                leaveType.includes(searchTerm) ||
                reason.includes(searchTerm) ||
                contactNumber.includes(searchTerm) ||
                nationalId.includes(searchTerm) ||
                email.includes(searchTerm) ||
                price.includes(searchTerm) ||
                status.includes(searchTerm);

            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesMonth = !monthFilter || rowMonth === monthFilter;

            row.style.display = (matchesSearch && matchesStatus && matchesMonth) ? '' : 'none';
        }
    }

    function populateMonthFilter() {
        const table = document.getElementById('leaveRequestsTable');
        const monthFilter = document.getElementById('monthFilter');
        if (!table || !monthFilter) return;

        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        const monthMap = new Map();

        for (let row of rows) {
            const createdDateText = row.cells[10].textContent.trim();
            const createdDate = new Date(createdDateText);
            if (Number.isNaN(createdDate.getTime())) continue;

            const value = `${createdDate.getFullYear()}-${String(createdDate.getMonth() + 1).padStart(2, '0')}`;
            const label = createdDate.toLocaleString('default', {
                month: 'long',
                year: 'numeric'
            });
            monthMap.set(value, label);
        }

        const sortedMonths = Array.from(monthMap.entries()).sort((a, b) => b[0].localeCompare(a[0]));
        for (const [value, label] of sortedMonths) {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = label;
            monthFilter.appendChild(option);
        }
    }

    document.getElementById('searchInput')?.addEventListener('input', applyFilters);
    document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
    document.getElementById('monthFilter')?.addEventListener('change', applyFilters);
    populateMonthFilter();
</script>

<!-- ======================================================= -->
<!-- Controller Validation Snippets (Reference) -->
<!-- ======================================================= -->

<?php
// Controller: collect values
$data = [
    'contact_number_value' => trim($_POST['contact_number'] ?? ''),
    'id_number_value' => trim($_POST['id_number'] ?? ''),
    'email_value' => trim($_POST['email'] ?? ''),
    'start_date_value' => trim($_POST['start_date'] ?? ''),
    'end_date_value' => trim($_POST['end_date'] ?? ''),
    'price_value' => trim($_POST['price'] ?? ''),
    'description_value' => trim($_POST['description'] ?? ''),

    'contact_number_err' => '',
    'id_number_err' => '',
    'email_err' => '',
    'start_date_err' => '',
    'end_date_err' => '',
    'price_err' => '',
    'description_err' => ''
];

// Validate contact number (10 digits, starts with 07)
if (empty($data['contact_number_value'])) {
    $data['contact_number_err'] = 'Please enter contact number';
} elseif (!preg_match('/^07[0-9]{8}$/', $data['contact_number_value'])) {
    $data['contact_number_err'] = 'Contact number must be 10 digits, start with 07, and contain only numbers';
}

// Validate ID number (exactly 12 digits)
if (empty($data['id_number_value'])) {
    $data['id_number_err'] = 'Please enter your ID number';
} elseif (!preg_match('/^[0-9]{12}$/', $data['id_number_value'])) {
    $data['id_number_err'] = 'ID number must be exactly 12 digits';
}

// Validate email
if (empty($data['email_value'])) {
    $data['email_err'] = 'Please enter email';
} elseif (!filter_var($data['email_value'], FILTER_VALIDATE_EMAIL)) {
    $data['email_err'] = 'Please enter a valid email address';
}

// Validate start date
if (empty($data['start_date_value'])) {
    $data['start_date_err'] = 'Please select a start date';
} elseif (strtotime($data['start_date_value']) < strtotime(date('Y-m-d'))) {
    $data['start_date_err'] = 'Start date cannot be in the past';
}

// Validate end date
if (empty($data['end_date_value'])) {
    $data['end_date_err'] = 'Please select an end date';
} elseif (!empty($data['start_date_value']) && strtotime($data['end_date_value']) < strtotime($data['start_date_value'])) {
    $data['end_date_err'] = 'End date must be after or equal to start date';
}

// Validate price
if ($data['price_value'] === '') {
    $data['price_err'] = 'Please enter price';
} elseif (!preg_match('/^\d+(\.\d{1,2})?$/', $data['price_value'])) {
    $data['price_err'] = 'Price must be a valid number (up to 2 decimal places)';
} elseif ((float)$data['price_value'] <= 0) {
    $data['price_err'] = 'Price must be greater than 0';
}

// Validate text description
if (empty($data['description_value'])) {
    $data['description_err'] = 'Please enter description';
} elseif (strlen($data['description_value']) < 10) {
    $data['description_err'] = 'Description must be at least 10 characters';
} elseif (strlen($data['description_value']) > 500) {
    $data['description_err'] = 'Description must be less than 500 characters';
}

// Final no-error check
if (
    empty($data['contact_number_err']) &&
    empty($data['id_number_err']) &&
    empty($data['email_err']) &&
    empty($data['start_date_err']) &&
    empty($data['end_date_err']) &&
    empty($data['price_err']) &&
    empty($data['description_err'])
) {
    // save data
}
?>

ALTER TABLE leave_requests
MODIFY COLUMN leave_type ENUM(
'Sick Leave',
'Annual Leave',
'Emergency Leave',
'Maternity Leave',
'Paternity Leave',
'Other',
'Compassionate Leave'
) NOT NULL;