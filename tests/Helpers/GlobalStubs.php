<?php

// Global stub functions used in controllers during tests
if (!function_exists('flash')) {
    function flash($name, $message = '', $class = '') {
        // record flash calls for assertions (ensure array is initialized)
        if (!isset($GLOBALS['test_flashes']) || !is_array($GLOBALS['test_flashes'])) {
            $GLOBALS['test_flashes'] = [];
        }
        $GLOBALS['test_flashes'][] = ['name' => $name, 'message' => $message, 'class' => $class];
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        // record redirects for assertions (ensure array is initialized)
        if (!isset($GLOBALS['test_redirects']) || !is_array($GLOBALS['test_redirects'])) {
            $GLOBALS['test_redirects'] = [];
        }
        $GLOBALS['test_redirects'][] = $url;
    }
}

if (!function_exists('uploadImage')) {
    function uploadImage($tmpName, $destName, $path) {
        // In tests we default to true unless specifically testing upload failures
        if (array_key_exists('test_upload_result', $GLOBALS)) {
            return $GLOBALS['test_upload_result'];
        }
        return true;
    }
}

if (!function_exists('deleteImage')) {
    function deleteImage($path) {
        if (!isset($GLOBALS['test_deleted_images']) || !is_array($GLOBALS['test_deleted_images'])) {
            $GLOBALS['test_deleted_images'] = [];
        }
        $GLOBALS['test_deleted_images'][] = $path;
    }
}

// Stub for sending templated emails to avoid external network calls in tests
if (!function_exists('send_templated_email')) {
    function send_templated_email($to, $templateName, $vars = [], $subject = null, $attachments = []) {
        return ['success' => true];
    }
}

if (!function_exists('render_email_template')) {
    function render_email_template($templateName, $vars = []) {
        return '<html></html>';
    }
}
