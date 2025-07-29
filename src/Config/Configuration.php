<?php

declare(strict_types=1);

namespace Dhiraj\PhpFileManager\Config;

/**
 * Configuration class for PHP File Manager
 *
 * This class handles all configuration settings for the file manager
 * including security settings, file size limits, and allowed extensions.
 *
 * @package Dhiraj\PhpFileManager\Config
 * @author Dhiraj Dhiman <dhiraj@nishthatechnosoft.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class Configuration
{
    /**
     * Default configuration values
     */
    private const DEFAULT_CONFIG = [
        'root_path' => null,
        'upload_path' => null,
        'max_file_size' => 50 * 1024 * 1024, // 50MB
        'allowed_extensions' => [
            'txt', 'php', 'html', 'css', 'js', 'json', 'xml', 'md',
            'log', 'zip', 'tar', 'gz', 'pdf', 'doc', 'docx', 'xls',
            'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'svg',
            'mp3', 'wav', 'mp4', 'avi', 'mov'
        ],
        'password' => 'admin123',
        'session_name' => 'php_file_manager',
        'enable_compression' => true,
        'enable_bulk_operations' => true,
        'enable_archive_operations' => true,
    ];

    /**
     * Current configuration
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Configuration constructor
     *
     * @param array<string, mixed> $config Custom configuration options
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge(self::DEFAULT_CONFIG, $config);
        $this->initializePaths();
    }

    /**
     * Initialize default paths if not provided
     *
     * @return void
     */
    private function initializePaths(): void
    {
        if ($this->config['root_path'] === null) {
            $this->config['root_path'] = $_SERVER['DOCUMENT_ROOT'] ?? getcwd();
        }

        if ($this->config['upload_path'] === null) {
            $this->config['upload_path'] = $this->config['root_path'] . '/uploads';
        }
    }

    /**
     * Get configuration value
     *
     * @param string $key Configuration key
     * @param mixed $default Default value if key not found
     * @return mixed Configuration value
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Set configuration value
     *
     * @param string $key Configuration key
     * @param mixed $value Configuration value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    /**
     * Get root path
     *
     * @return string Root path
     */
    public function getRootPath(): string
    {
        return (string) $this->config['root_path'];
    }

    /**
     * Get upload path
     *
     * @return string Upload path
     */
    public function getUploadPath(): string
    {
        return (string) $this->config['upload_path'];
    }

    /**
     * Get maximum file size
     *
     * @return int Maximum file size in bytes
     */
    public function getMaxFileSize(): int
    {
        return (int) $this->config['max_file_size'];
    }

    /**
     * Get allowed extensions
     *
     * @return array<int, string> Array of allowed file extensions
     */
    public function getAllowedExtensions(): array
    {
        return (array) $this->config['allowed_extensions'];
    }

    /**
     * Get authentication password
     *
     * @return string Authentication password
     */
    public function getPassword(): string
    {
        return (string) $this->config['password'];
    }

    /**
     * Get session name
     *
     * @return string Session name
     */
    public function getSessionName(): string
    {
        return (string) $this->config['session_name'];
    }

    /**
     * Check if compression is enabled
     *
     * @return bool True if compression is enabled
     */
    public function isCompressionEnabled(): bool
    {
        return (bool) $this->config['enable_compression'];
    }

    /**
     * Check if bulk operations are enabled
     *
     * @return bool True if bulk operations are enabled
     */
    public function isBulkOperationsEnabled(): bool
    {
        return (bool) $this->config['enable_bulk_operations'];
    }

    /**
     * Check if archive operations are enabled
     *
     * @return bool True if archive operations are enabled
     */
    public function isArchiveOperationsEnabled(): bool
    {
        return (bool) $this->config['enable_archive_operations'];
    }

    /**
     * Validate file extension
     *
     * @param string $filename Filename to validate
     * @return bool True if extension is allowed
     */
    public function isExtensionAllowed(string $filename): bool
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, $this->getAllowedExtensions(), true);
    }

    /**
     * Get all configuration as array
     *
     * @return array<string, mixed> Complete configuration array
     */
    public function toArray(): array
    {
        return $this->config;
    }
}
