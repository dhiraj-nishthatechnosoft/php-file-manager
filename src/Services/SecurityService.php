<?php

declare(strict_types=1);

namespace Dhiraj\PhpFileManager\Services;

use Dhiraj\PhpFileManager\Config\Configuration;

/**
 * Security service for file manager operations
 *
 * This service handles all security-related operations including
 * path validation, file type checking, and access control.
 *
 * @package Dhiraj\PhpFileManager\Services
 * @author Dhiraj Dhiman <dhiraj@example.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class SecurityService
{
    /**
     * Configuration instance
     */
    private Configuration $config;

    /**
     * SecurityService constructor
     *
     * @param Configuration $config Configuration instance
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * Check if a path is safe and within allowed boundaries
     *
     * @param string $path Path to validate
     * @return bool True if path is safe
     */
    public function isPathSafe(string $path): bool
    {
        $realPath = realpath($path);
        $rootPath = $this->config->getRootPath();

        // Path must exist and be within root directory
        if (!$realPath || strpos($realPath, $rootPath) !== 0) {
            return false;
        }

        // Check for directory traversal attempts
        if (strpos($path, '..') !== false) {
            return false;
        }

        return true;
    }

    /**
     * Validate file extension
     *
     * @param string $filename Filename to validate
     * @return bool True if extension is allowed
     */
    public function isFileTypeAllowed(string $filename): bool
    {
        return $this->config->isExtensionAllowed($filename);
    }

    /**
     * Validate file size
     *
     * @param int $fileSize File size in bytes
     * @return bool True if file size is within limits
     */
    public function isFileSizeAllowed(int $fileSize): bool
    {
        return $fileSize <= $this->config->getMaxFileSize();
    }

    /**
     * Sanitize filename for safe storage
     *
     * @param string $filename Original filename
     * @return string Sanitized filename
     */
    public function sanitizeFilename(string $filename): string
    {
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $filename);

        // Remove multiple dots
        $filename = preg_replace('/\.+/', '.', $filename);

        // Trim dots and underscores from start and end
        $filename = trim($filename, '._');

        // Ensure filename is not empty
        if (empty($filename)) {
            $filename = 'file_' . time();
        }

        return $filename;
    }

    /**
     * Create directory recursively with security checks
     *
     * @param string $path Path to create
     * @return bool True if directory was created or already exists
     */
    public function createDirectoryRecursive(string $path): bool
    {
        // Convert relative path to absolute path
        $absolutePath = $this->config->getRootPath() . '/' . ltrim($path, '/');

        // Security check
        if (!$this->isPathSafe($absolutePath)) {
            return false;
        }

        if (!file_exists($absolutePath)) {
            return mkdir($absolutePath, 0755, true);
        }

        return is_dir($absolutePath);
    }

    /**
     * Validate and sanitize POST data
     *
     * @param array<string, mixed> $data POST data
     * @return array<string, mixed> Sanitized data
     */
    public function sanitizePostData(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizePostData($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Generate secure token for CSRF protection
     *
     * @return string Secure token
     */
    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Validate CSRF token
     *
     * @param string $token Token to validate
     * @param string $sessionToken Session token
     * @return bool True if token is valid
     */
    public function validateToken(string $token, string $sessionToken): bool
    {
        return hash_equals($sessionToken, $token);
    }

    /**
     * Check if a file is potentially dangerous
     *
     * @param string $filename Filename to check
     * @return bool True if file is potentially dangerous
     */
    public function isDangerousFile(string $filename): bool
    {
        $dangerousExtensions = [
            'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js',
            'jar', 'php', 'asp', 'aspx', 'jsp', 'py', 'rb', 'pl'
        ];

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, $dangerousExtensions, true);
    }

    /**
     * Log security event
     *
     * @param string $event Event description
     * @param array<string, mixed> $context Additional context
     * @return void
     */
    public function logSecurityEvent(string $event, array $context = []): void
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'context' => $context
        ];

        error_log('FileManager Security: ' . json_encode($logEntry));
    }
}
