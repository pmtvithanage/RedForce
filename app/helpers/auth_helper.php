<?php
/**
 * Authentication Helper Functions
 * Provides common authentication and session management functions
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user has specific role
 * @param string $role
 * @return bool
 */
function hasRole($role) {
    if (!isLoggedIn()) {
        return false;
    }
    return strtolower($_SESSION['user_role']) === strtolower($role);
}

/**
 * Check if user is admin
 * @return bool
 */
function isAdmin() {
    return hasRole('admin');
}

/**
 * Check if user is supervisor
 * @return bool
 */
function isSupervisor() {
    return hasRole('supervisor');
}

/**
 * Check if user is premise officer
 * @return bool
 */
function isPremiseOfficer() {
    return hasRole('premise officer') || hasRole('premiseofficer');
}

/**
 * Check if user is mobile rider
 * @return bool
 */
function isMobileRider() {
    return hasRole('mobile rider') || hasRole('mobilerider');
}

/**
 * Check if user is client
 * @return bool
 */
function isClient() {
    return hasRole('client');
}

/**
 * Check if user is guest
 * @return bool
 */
function isCareTaker() {
    return hasRole('care taker') || hasRole('caretaker');
}

/**
 * Get current user ID
 * @return int|null
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user name
 * @return string|null
 */
function getCurrentUserName() {
    return $_SESSION['user_name'] ?? null;
}

/**
 * Get current user role
 * @return string|null
 */
function getCurrentUserRole() {
    return $_SESSION['user_role'] ?? null;
}

/**
 * Get current user email
 * @return string|null
 */
function getCurrentUserEmail() {
    return $_SESSION['user_email'] ?? null;
}

/**
 * Redirect to specified URL
 * @param string $page
 */
function redirect($page) {
    header('location: ' . URL_ROOT . '/' . $page);
    exit();
}

/**
 * Flash message helper
 * @param string $name
 * @param string $message
 * @param string $class
 */
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

/**
 * Require authentication for page access
 * @param string $requiredRole
 */
function requireAuth($requiredRole = null) {
    if (!isLoggedIn()) {
        flash('login_error', 'Please log in to access this page', 'alert alert-danger');
        redirect('users/login');
    }
    
    if ($requiredRole && !hasRole($requiredRole)) {
        flash('access_error', 'You do not have permission to access this page', 'alert alert-danger');
        redirect('users/login');
    }
}

/**
 * Log user activity
 * @param string $action
 * @param string $description
 */
function logActivity($action, $description = '') {
    if (isLoggedIn()) {
        $userId = getCurrentUserId();
        $userName = getCurrentUserName();
        $userRole = getCurrentUserRole();
        
        // You can implement logging to database here
        error_log("User Activity: {$userName} ({$userRole}) - {$action}: {$description}");
    }
}
?>
