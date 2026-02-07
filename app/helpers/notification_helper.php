<?php
/**
 * Notification Helper Functions
 */

/**
 * Convert a timestamp to a human-readable "time ago" format
 * @param string $datetime A datetime string
 * @return string Time elapsed in human-readable format (e.g., "5 minutes ago")
 */
function time_elapsed_string($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Calculate weeks and remaining days without modifying the DateInterval object
    $weeks = floor($diff->d / 7);
    $days = $diff->d - ($weeks * 7);

    $string = array(
        'y' => $diff->y,
        'm' => $diff->m,
        'w' => $weeks,
        'd' => $days,
        'h' => $diff->h,
        'i' => $diff->i,
        's' => $diff->s,
    );
    
    $labels = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    
    $result = array();
    foreach ($string as $k => $v) {
        if ($v) {
            $result[$k] = $v . ' ' . $labels[$k] . ($v > 1 ? 's' : '');
        }
    }

    if (empty($result)) {
        return 'just now';
    }

    $result = array_slice($result, 0, 1);
    return implode(', ', $result) . ' ago';
}
