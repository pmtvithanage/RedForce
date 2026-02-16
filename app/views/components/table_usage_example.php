<?php
/**
 * Table Component Usage Examples
 * 
 * This file demonstrates how to use the reusable table component
 */

// Example 1: Simple table with array data
$tableConfig = [
    'title' => 'Leave History',
    'headers' => ['Leave Type', 'Reason', 'Start Date', 'End Date', 'Status', 'Actions'],
    'data' => '', // Will be filled below
    'emptyMessage' => 'No leave requests found',
    'sectionClass' => 'table-section'
];

// Build table rows HTML
ob_start();
?>
<?php if (!empty($data['leaveRequests'])): ?>
    <?php foreach($data['leaveRequests'] as $leave): ?>
        <tr>
            <td><?= htmlspecialchars($leave->leave_type) ?></td>
            <td><?= htmlspecialchars($leave->reason) ?></td>
            <td><?= date('d/m/Y', strtotime($leave->start_date)) ?></td>
            <td><?= date('d/m/Y', strtotime($leave->end_date)) ?></td>
            <td>
                <span class="status <?= strtolower($leave->status) ?>">
                    <?= $leave->status ?>
                </span>
            </td>
            <td>
                <button class="btn-action">Delete</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
<?php
$tableConfig['data'] = ob_get_clean();

// Include the table component
// include APP_ROOT . '/views/components/table.php';


// Example 2: Equipment requests table
$equipmentTableConfig = [
    'title' => 'Equipment Requests',
    'headers' => ['Date', 'Caretaker', 'Equipment', 'Quantity', 'Status', 'Actions'],
    'data' => '', // Your table rows HTML here
    'emptyMessage' => 'No equipment requests found',
    'sectionClass' => 'table-section'
];

// Example 3: Minimal table
$minimalTableConfig = [
    'title' => 'User List',
    'headers' => ['Name', 'Email', 'Role'],
    'data' => '<tr><td>John Doe</td><td>john@example.com</td><td>Admin</td></tr>',
    'sectionClass' => 'table-section'
];
?>
