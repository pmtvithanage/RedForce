<?php

namespace Tests\Helpers;

class ErrorSuppressor
{
    private static $oldErrorReporting;
    private static $oldDisplayErrors;
    private static $oldErrorLog;
    private static $oldLogErrors;

    /**
     * Suppress error logs and minor notices/warnings during tests.
     */
    public static function suppress(): void
    {
        // Preserve current settings
        self::$oldErrorReporting = error_reporting();
        self::$oldDisplayErrors = ini_get('display_errors');
        self::$oldErrorLog = ini_get('error_log');
        self::$oldLogErrors = ini_get('log_errors');

        // Disable display of errors in test output
        ini_set('display_errors', '0');
        // Redirect error_log to /dev/null so messages are not written
        ini_set('error_log', '/dev/null');
        // Turn off low-level PHP error logging
        ini_set('log_errors', '0');

        // Lower reported errors to omit notices and warnings
        error_reporting(self::$oldErrorReporting & ~E_NOTICE & ~E_WARNING);
    }

    /**
     * Restore previous error settings
     */
    public static function restore(): void
    {
        // Restore ini settings and error_reporting
        ini_set('error_log', self::$oldErrorLog ?: '');
        ini_set('display_errors', self::$oldDisplayErrors ?: '1');
        ini_set('log_errors', self::$oldLogErrors ?: '1');
        error_reporting(self::$oldErrorReporting ?? E_ALL);
    }
}
