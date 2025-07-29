<?php

declare(strict_types=1);

namespace NishthaTechnosoft\PhpFileManager;

use NishthaTechnosoft\PhpFileManager\Config\Configuration;
use NishthaTechnosoft\PhpFileManager\Controllers\FileController;
use NishthaTechnosoft\PhpFileManager\Services\AuthenticationService;
use NishthaTechnosoft\PhpFileManager\Services\SecurityService;
use NishthaTechnosoft\PhpFileManager\Resources\ResourceManager;

/**
 * Main PHP File Manager class
 *
 * This is the main entry point for the PHP File Manager application.
 * It handles initialization, routing, and provides the public API.
 *
 * @package Dhiraj\PhpFileManager
 * @author Dhiraj Dhiman <dhiraj@nishthatechnosoft.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class FileManager
{
    /**
     * Configuration instance
     */
    private Configuration $config;

    /**
     * Authentication service instance
     */
    private AuthenticationService $auth;

    /**
     * Security service instance
     */
    private SecurityService $security;

    /**
     * File controller instance
     */
    private FileController $fileController;

    /**
     * Resource manager instance
     */
    private ResourceManager $resourceManager;

    /**
     * FileManager constructor
     *
     * @param array<string, mixed> $config Configuration options
     */
    public function __construct(array $config = [])
    {
        $this->config = new Configuration($config);
        $this->auth = new AuthenticationService($this->config);
        $this->security = new SecurityService($this->config);
        $this->fileController = new FileController($this->config, $this->security);
        $this->resourceManager = new ResourceManager();

        // Configure and start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            // Configure session settings
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_strict_mode', '1');
            ini_set('session.cookie_samesite', 'Strict');

            // Start session with custom name
            session_name($this->config->getSessionName());
            session_start();
        }
    }

    /**
     * Run the file manager application
     *
     * @return void
     */
    public function run(): void
    {
        // Handle authentication
        if (!$this->auth->isAuthenticated()) {
            // Check if login attempt is being made
            if (isset($_POST['password']) && !empty($_POST['password'])) {
                if ($this->auth->attemptLogin($_POST['password'])) {
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit;
                }
            }
            $this->renderLoginForm();
            return;
        }

        // Handle logout
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            $this->auth->logout();
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }

        // Get current directory
        $currentDir = $this->getCurrentDirectory();

        // Handle POST actions
        if (isset($_POST['action'])) {
            $this->fileController->handleAction($_POST, $currentDir);
        }

        // Handle GET actions
        if (isset($_GET['action'])) {
            $this->handleGetAction($_GET, $currentDir);
        }

        // Render main interface
        $this->renderMainInterface($currentDir);
    }

    /**
     * Get current directory with security validation
     *
     * @return string Validated current directory path
     */
    private function getCurrentDirectory(): string
    {
        $requestedDir = $_GET['dir'] ?? $this->config->getRootPath();
        $currentDir = realpath($requestedDir) ?: $this->config->getRootPath();

        if (!$this->security->isPathSafe($currentDir)) {
            return $this->config->getRootPath();
        }

        return is_dir($currentDir) ? $currentDir : $this->config->getRootPath();
    }

    /**
     * Handle GET actions
     *
     * @param array<string, mixed> $params GET parameters
     * @param string $currentDir Current directory
     * @return void
     */
    private function handleGetAction(array $params, string $currentDir): void
    {
        $action = $params['action'] ?? '';

        switch ($action) {
            case 'edit':
                $this->fileController->showFileEditor($params['file'] ?? '', $currentDir);
                exit;
            case 'download':
                $this->fileController->downloadFile($params['file'] ?? '', $currentDir);
                exit;
            case 'create_archive':
                $this->fileController->createArchive($currentDir, $params['path'] ?? '');
                exit;
            case 'get_path_suggestions':
                $suggestions = $this->fileController->getPathSuggestions($params['path'] ?? '/', $currentDir);
                header('Content-Type: application/json');
                echo json_encode(['suggestions' => $suggestions]);
                exit;
        }
    }

    /**
     * Render login form
     *
     * @return void
     */
    private function renderLoginForm(): void
    {
        $viewPath = $this->resourceManager->getViewPath('login');
        $error = isset($_POST['password']) ? 'Invalid password' : '';

        // Include view with access to $this context
        include $viewPath;
    }

    /**
     * Render main interface
     *
     * @param string $currentDir Current directory
     * @return void
     */
    private function renderMainInterface(string $currentDir): void
    {
        $files = $this->fileController->getDirectoryListing($currentDir);
        $viewPath = $this->resourceManager->getViewPath('main');

        // Include view with access to $this context
        include $viewPath;
    }

    /**
     * Get file icon based on file extension
     *
     * @param string $filename Filename
     * @return string File icon emoji
     */
    public function getFileIcon(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $icons = [
            // Programming files
            'php' => '🐘',
            'html' => '🌐',
            'css' => '🎨',
            'js' => '⚡',
            'py' => '🐍',
            'java' => '☕',
            'cpp' => '⚙️',
            'c' => '⚙️',
            'cs' => '🔷',
            'rb' => '💎',
            'go' => '🔵',
            'rust' => '🦀',
            'swift' => '🍎',

            // Data files
            'json' => '📋',
            'xml' => '📄',
            'csv' => '📊',
            'sql' => '🔍',
            'yml' => '⚙️',
            'yaml' => '⚙️',

            // Documents
            'txt' => '📝',
            'md' => '📝',
            'pdf' => '📄',
            'doc' => '📄',
            'docx' => '📄',
            'xls' => '📈',
            'xlsx' => '📈',
            'ppt' => '📊',
            'pptx' => '📊',

            // Images
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'gif' => '🖼️',
            'svg' => '🖼️',
            'bmp' => '🖼️',
            'ico' => '🖼️',

            // Audio
            'mp3' => '🎵',
            'wav' => '🎵',
            'flac' => '🎵',
            'aac' => '🎵',

            // Video
            'mp4' => '🎬',
            'avi' => '🎬',
            'mkv' => '🎬',
            'mov' => '🎬',

            // Archives
            'zip' => '📦',
            'rar' => '📦',
            '7z' => '📦',
            'tar' => '📦',
            'gz' => '📦',
            'tgz' => '📦',

            // Config files
            'conf' => '⚙️',
            'ini' => '⚙️',
            'cfg' => '⚙️',

            // Log files
            'log' => '📋',
        ];

        return $icons[$extension] ?? '📄';
    }

    /**
     * Format bytes into human readable format
     *
     * @param int $size Size in bytes
     * @param int $precision Decimal precision
     * @return string Formatted size
     */
    public function formatBytes(int $size, int $precision = 2): string
    {
        if ($size == 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $base = log($size, 1024);
        $unitIndex = floor($base);

        if ($unitIndex >= count($units)) {
            $unitIndex = count($units) - 1;
        }

        $value = round(pow(1024, $base - $unitIndex), $precision);

        return $value . ' ' . $units[$unitIndex];
    }

    /**
     * Get configuration instance
     *
     * @return Configuration Configuration instance
     */
    public function getConfig(): Configuration
    {
        return $this->config;
    }

    /**
     * Get authentication service instance
     *
     * @return AuthenticationService Authentication service instance
     */
    public function getAuth(): AuthenticationService
    {
        return $this->auth;
    }

    /**
     * Get security service instance
     *
     * @return SecurityService Security service instance
     */
    public function getSecurity(): SecurityService
    {
        return $this->security;
    }

    /**
     * Get file controller instance
     *
     * @return FileController File controller instance
     */
    public function getFileController(): FileController
    {
        return $this->fileController;
    }

    /**
     * Get resource manager instance
     *
     * @return ResourceManager Resource manager instance
     */
    public function getResourceManager(): ResourceManager
    {
        return $this->resourceManager;
    }
}
