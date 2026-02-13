# Table Component Documentation

## Overview
A reusable table component with styling matching `v_leaverequests.php`. This component provides consistent table styling across the application.

## Features
- Clean, responsive design
- White background with shadow
- Hover effects on rows
- Built-in status badges (approved, pending, rejected)
- Fully customizable headers and content
- Empty state handling
- Mobile-friendly overflow scrolling

## Usage

### Basic Example

```php
<?php
// Prepare your table configuration
$tableConfig = [
    'title' => 'Leave History',
    'headers' => ['Leave Type', 'Start Date', 'End Date', 'Status'],
    'data' => '', // Will contain HTML rows
    'emptyMessage' => 'No leave requests found'
];

// Build your table rows
ob_start();
foreach($yourData as $row) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row->column1) . '</td>';
    echo '<td>' . htmlspecialchars($row->column2) . '</td>';
    echo '</tr>';
}
$tableConfig['data'] = ob_get_clean();

// Include the component
include APP_ROOT . '/views/components/table.php';
?>
```

### Complete Example with Status Badges

```php
<?php
$tableConfig = [
    'title' => 'Equipment Requests',
    'headers' => ['Date', 'Item', 'Quantity', 'Status', 'Actions'],
    'emptyMessage' => 'No requests found'
];

ob_start();
if (!empty($data['requests'])) {
    foreach($data['requests'] as $request) {
        ?>
        <tr>
            <td><?= date('d/m/Y', strtotime($request->date)) ?></td>
            <td><?= htmlspecialchars($request->item_name) ?></td>
            <td><?= $request->quantity ?></td>
            <td>
                <span class="status <?= strtolower($request->status) ?>">
                    <?= $request->status ?>
                </span>
            </td>
            <td>
                <button class="btn-view">View</button>
            </td>
        </tr>
        <?php
    }
}
$tableConfig['data'] = ob_get_clean();

include APP_ROOT . '/views/components/table.php';
?>
```

## Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `title` | string | 'Data Table' | Table section heading |
| `headers` | array | [] | Array of column header names |
| `data` | string | '' | HTML content for table rows |
| `emptyMessage` | string | 'No data available' | Message shown when no data |
| `sectionClass` | string | 'table-section' | Custom CSS class for section |

## Status Badge Classes

The component includes three built-in status badge styles:

- `.status.approved` - Green badge for approved items
- `.status.pending` - Yellow badge for pending items
- `.status.rejected` - Red badge for rejected items

Usage:
```html
<span class="status approved">Approved</span>
<span class="status pending">Pending</span>
<span class="status rejected">Rejected</span>
```

## Styling

The component uses embedded CSS that matches `v_leaverequests.php`:
- White background (#ffffff)
- Border radius: 12px
- Box shadow: 0 2px 10px rgba(0, 0, 0, 0.1)
- Table padding: 12px 10px
- Hover effect: #f8f9fa background

## Responsive Design

The table automatically:
- Adjusts margins on mobile (768px breakpoint)
- Enables horizontal scrolling for wide tables
- Reduces padding on smaller screens

## Customization

To add custom styling, use the `sectionClass` option:

```php
$tableConfig = [
    'title' => 'Custom Table',
    'headers' => ['Col 1', 'Col 2'],
    'data' => '...',
    'sectionClass' => 'my-custom-table'
];
```

Then add CSS:
```css
.my-custom-table h2 {
    color: #c41212;
}
```

## Notes

- Always use `htmlspecialchars()` for user-generated content
- The `data` parameter accepts raw HTML for maximum flexibility
- Headers count should match your table columns for proper empty state display
- Component includes its own CSS (no external stylesheet required)
