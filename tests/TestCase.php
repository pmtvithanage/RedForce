<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

// Global test helper functions
if (!function_exists('flash')) {
    function flash($name, $message = '', $class = '') {
        // no-op in tests
    }
}
if (!function_exists('redirect')) {
    function redirect($url) {
        // no-op in tests
    }
}
if (!function_exists('uploadImage')) {
    function uploadImage($tmpName, $destName, $path) {
        // default to true for tests if called unexpectedly
        return true;
    }
}

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Load your application bootstrap if needed
        if (file_exists(__DIR__ . '/../app/bootstrap.php')) {
            require_once __DIR__ . '/../app/bootstrap.php';
        }
        
        // Set up test environment
        $this->setUpTestEnvironment();
    }
    
    /**
     * Clean up after each test
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up any resources
        $this->tearDownTestEnvironment();
    }
    
    /**
     * Setup test environment
     */
    protected function setUpTestEnvironment(): void
    {
        // Initialize test database, sessions, etc.
        if (isset($_SESSION)) {
            $_SESSION = [];
        }

        // Load global test stubs and suppress error log noise
        if (file_exists(__DIR__ . '/Helpers/GlobalStubs.php')) {
            require_once __DIR__ . '/Helpers/GlobalStubs.php';
        }

        // Define minimal PUB_ROOT used by controllers for file operations
        if (!defined('PUB_ROOT')) {
            define('PUB_ROOT', sys_get_temp_dir());
        }

        if (file_exists(__DIR__ . '/Helpers/ErrorSuppressor.php')) {
            require_once __DIR__ . '/Helpers/ErrorSuppressor.php';
            \Tests\Helpers\ErrorSuppressor::suppress();
        }
    }
    
    /**
     * Clean up test environment
     */
    protected function tearDownTestEnvironment(): void
    {
        // Clean up after tests
        if (isset($_SESSION)) {
            session_destroy();
        }

        // Restore error handling settings if we changed them
        if (class_exists('\\Tests\\Helpers\\ErrorSuppressor')) {
            \Tests\Helpers\ErrorSuppressor::restore();
        }
    }
    
    /**
     * Helper to invoke private/protected methods
     */
    protected function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        
        return $method->invokeArgs($object, $parameters);
    }
}