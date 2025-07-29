<?php

/**
 * PHP File Manager - Public Entry Point
 *
 * This is the main entry point for the PHP File Manager application.
 * It initializes the file manager with default or custom configuration.
 *
 * @package NishthaTechnosoft\PhpFileManager
 * @version 1.0.0
 * @author Dhiraj Dhiman
 */

declare(strict_types=1);

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Include composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use NishthaTechnosoft\PhpFileManager\FileManager;

try {
    // Default configuration - customize as needed
    $config = [
        'root_path' => $_SERVER['DOCUMENT_ROOT'] ?? __DIR__,
        'password' => 'admin123', // Change this password!
        'session_name' => 'php_file_manager',
        'max_file_size' => 50 * 1024 * 1024, // 50MB
        'allowed_extensions' => [
            'txt', 'php', 'html', 'css', 'js', 'json', 'xml', 'md', 'log',
            'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'zip', 'tar', 'gz', 'rar', '7z'
        ],
        'enable_compression' => true,
        'enable_bulk_operations' => true,
        'enable_archive_operations' => true,
    ];

    // You can override configuration from environment variables
    if (isset($_ENV['FM_ROOT_PATH'])) {
        $config['root_path'] = $_ENV['FM_ROOT_PATH'];
    }

    if (isset($_ENV['FM_PASSWORD'])) {
        $config['password'] = $_ENV['FM_PASSWORD'];
    }

    if (isset($_ENV['FM_MAX_FILE_SIZE'])) {
        $config['max_file_size'] = (int) $_ENV['FM_MAX_FILE_SIZE'];
    }

    // Create and run the file manager
    $fileManager = new FileManager($config);
    $fileManager->run();
} catch (Throwable $e) {
    // Handle any uncaught exceptions
    http_response_code(500);

    // In production, you might want to log the error and show a generic message
    if (ini_get('display_errors')) {
        echo '<h1>Error</h1>';
        echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . '</p>';
        echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        echo '<h1>Internal Server Error</h1>';
        echo '<p>An error occurred while processing your request.</p>';
    }
}
