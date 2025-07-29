<?php

/**
 * PHP File Manager Configuration
 * 
 * Copy this file and customize it according to your needs.
 */

return [
    // Root path for file operations (null = document root)
    'root_path' => null,
    
    // Upload directory (null = root_path/uploads)
    'upload_path' => null,
    
    // Maximum file size for uploads (in bytes)
    'max_file_size' => 50 * 1024 * 1024, // 50MB
    
    // Allowed file extensions
    'allowed_extensions' => [
        'txt', 'php', 'html', 'css', 'js', 'json', 'xml', 'md',
        'log', 'zip', 'tar', 'gz', 'pdf', 'doc', 'docx', 'xls',
        'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'svg',
        'mp3', 'wav', 'mp4', 'avi', 'mov'
    ],
    
    // Authentication password
    'password' => 'admin123',
    
    // Session configuration
    'session_name' => 'php_file_manager',
    
    // Feature toggles
    'enable_compression' => true,
    'enable_bulk_operations' => true,
    'enable_archive_operations' => true,
];