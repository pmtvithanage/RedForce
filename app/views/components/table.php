<?php
/**
 * Reusable Table Component
 * 
 * Usage:
 * $tableConfig = [
 *     'title' => 'Your Table Title',
 *     'headers' => ['Column 1', 'Column 2', 'Column 3'],
 *     'data' => [
 *         ['Value 1', 'Value 2', 'Value 3'],
 *         ['Value 4', 'Value 5', 'Value 6']
 *     ],
 *     'emptyMessage' => 'No data found',
 *     'sectionClass' => 'custom-section-class' // optional
 * ];
 * include APP_ROOT . '/views/components/table.php';
 */

// Default configuration
$title = $tableConfig['title'] ?? 'Data Table';
$headers = $tableConfig['headers'] ?? [];
$data = $tableConfig['data'] ?? [];
$emptyMessage = $tableConfig['emptyMessage'] ?? 'No data available';
$sectionClass = $tableConfig['sectionClass'] ?? 'table-section';
$renderCallback = $tableConfig['renderCallback'] ?? null;
?>

<style>
/* Table Component Styles - Matching v_leaverequests.php */
.table-section {
    background-color: white;
    margin: 0 30px 30px;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.table-section h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 22px;
    font-weight: 600;
}

.table-section .table-container {
    overflow-x: auto;
}

.table-section .data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table-section .data-table th {
    background-color: #f8f9fa;
    padding: 12px 10px;
    text-align: left;
    font-weight: 600;
    color: #555;
    border-bottom: 2px solid #e9ecef;
}

.table-section .data-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #e9ecef;
}

.table-section .data-table tbody tr:hover {
    background-color: #f8f9fa;
}

.table-section .empty-message {
    text-align: center;
    padding: 20px;
    color: #666;
}

/* Status Badges */
.table-section .status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.table-section .status.approved {
    background-color: #d4edda;
    color: #155724;
}

.table-section .status.pending {
    background-color: #fff3cd;
    color: #856404;
}

.table-section .status.rejected {
    background-color: #f8d7da;
    color: #721c24;
}

/* Responsive Design */
@media (max-width: 768px) {
    .table-section {
        margin: 15px;
        padding: 20px;
    }
    
    .table-section .table-container {
        -webkit-overflow-scrolling: touch;
    }
}
</style>

<section class="<?php echo htmlspecialchars($sectionClass); ?>">
    <h2><?php echo htmlspecialchars($title); ?></h2>
    <div class="table-container">
        <table class="data-table">
            <?php if (!empty($headers)): ?>
                <thead>
                    <tr>
                        <?php foreach ($headers as $header): ?>
                            <th><?php echo htmlspecialchars($header); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
            <?php endif; ?>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php 
                    if (is_callable($renderCallback)) {
                        // Use custom render callback function
                        foreach ($data as $row) {
                            call_user_func($renderCallback, $row);
                        }
                    } else {
                        // Default rendering for simple arrays
                        foreach ($data as $row): 
                    ?>
                        <tr>
                            <?php 
                            if (is_array($row) || is_object($row)) {
                                foreach ($row as $cell): 
                            ?>
                                <td><?php echo htmlspecialchars($cell); ?></td>
                            <?php 
                                endforeach;
                            }
                            ?>
                        </tr>
                    <?php 
                        endforeach;
                    }
                    ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo count($headers); ?>" class="empty-message">
                            <?php echo htmlspecialchars($emptyMessage); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
