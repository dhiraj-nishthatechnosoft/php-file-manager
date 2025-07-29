<?php

declare(strict_types=1);

namespace Dhiraj\PhpFileManager\Controllers;

use Dhiraj\PhpFileManager\Config\Configuration;
use Dhiraj\PhpFileManager\Services\SecurityService;
use Exception;
use PharData;
use ZipArchive;

/**
 * File operations controller
 *
 * This controller handles all file and directory operations including
 * creation, deletion, copying, moving, archiving, and more.
 *
 * @package Dhiraj\PhpFileManager\Controllers
 * @author Dhiraj Dhiman <dhiraj@nishthatechnosoft.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class FileController
{
    /**
     * Configuration instance
     */
    private Configuration $config;

    /**
     * Security service instance
     */
    private SecurityService $security;

    /**
     * FileController constructor
     *
     * @param Configuration $config Configuration instance
     * @param SecurityService $security Security service instance
     */
    public function __construct(Configuration $config, SecurityService $security)
    {
        $this->config = $config;
        $this->security = $security;
    }

    /**
     * Handle POST action requests
     *
     * @param array<string, mixed> $post POST data
     * @param string $currentDir Current directory
     * @return void
     */
    public function handleAction(array $post, string $currentDir): void
    {
        $post = $this->security->sanitizePostData($post);
        $action = $post['action'] ?? '';

        switch ($action) {
            case 'create_file':
                $this->createFile($post['filename'] ?? '', $currentDir);
                break;
            case 'create_folder':
                $this->createFolder($post['foldername'] ?? '', $currentDir);
                break;
            case 'delete':
                $this->deleteItem($post['item'] ?? '', $currentDir);
                break;
            case 'rename':
                $this->renameItem($post['old_name'] ?? '', $post['new_name'] ?? '', $currentDir);
                break;
            case 'save_file':
                $this->saveFile($post['filepath'] ?? '', $post['content'] ?? '');
                break;
            case 'upload':
                $this->uploadFile($_FILES['file'] ?? [], $currentDir);
                break;
            case 'bulk_delete':
                $this->bulkDelete($post['items'] ?? [], $currentDir);
                break;
            case 'bulk_archive':
                $this->bulkArchive($post['items'] ?? [], $currentDir);
                break;
            case 'create_archive_with_name':
                $this->createArchiveWithName(
                    $post['archive_name'] ?? '',
                    $post['items'] ?? [],
                    $currentDir
                );
                break;
            case 'unarchive':
                $this->unarchiveFile($post['archive_file'] ?? '', $currentDir);
                break;
            case 'copy':
                $this->copyItem($post['item'] ?? '', $post['destination'] ?? '', $currentDir);
                break;
            case 'move':
                $this->moveItem($post['item'] ?? '', $post['destination'] ?? '', $currentDir);
                break;
            case 'bulk_copy':
                $this->bulkCopy($post['items'] ?? [], $post['destination'] ?? '', $currentDir);
                break;
            case 'bulk_move':
                $this->bulkMove($post['items'] ?? [], $post['destination'] ?? '', $currentDir);
                break;
            case 'unarchive_to_path':
                $this->unarchiveToPath(
                    $post['archive_file'] ?? '',
                    $post['destination'] ?? '',
                    $post['conflict_resolution'] ?? 'ask',
                    $currentDir
                );
                break;
        }
    }

    /**
     * Create a new file
     *
     * @param string $filename Filename to create
     * @param string $dir Directory to create file in
     * @return void
     */
    public function createFile(string $filename, string $dir): void
    {
        if (empty($filename)) {
            $this->showMessage('error', 'Filename cannot be empty');
            return;
        }

        $filename = $this->security->sanitizeFilename($filename);
        $filepath = $dir . '/' . basename($filename);

        if (!$this->security->isPathSafe(dirname($filepath))) {
            $this->showMessage('error', 'Invalid path');
            return;
        }

        if (file_exists($filepath)) {
            $this->showMessage('error', 'File already exists');
            return;
        }

        if (file_put_contents($filepath, '') !== false) {
            $this->showMessage('success', 'File created successfully', true);
        } else {
            $this->showMessage('error', 'Failed to create file');
        }
    }

    /**
     * Create a new folder
     *
     * @param string $foldername Folder name to create
     * @param string $dir Directory to create folder in
     * @return void
     */
    public function createFolder(string $foldername, string $dir): void
    {
        if (empty($foldername)) {
            $this->showMessage('error', 'Folder name cannot be empty');
            return;
        }

        $foldername = $this->security->sanitizeFilename($foldername);
        $folderpath = $dir . '/' . basename($foldername);

        if (!$this->security->isPathSafe(dirname($folderpath))) {
            $this->showMessage('error', 'Invalid path');
            return;
        }

        if (file_exists($folderpath)) {
            $this->showMessage('error', 'Folder already exists');
            return;
        }

        if (mkdir($folderpath, 0755, true)) {
            $this->showMessage('success', 'Folder created successfully', true);
        } else {
            $this->showMessage('error', 'Failed to create folder');
        }
    }

    /**
     * Delete a file or directory
     *
     * @param string $item Item name to delete
     * @param string $dir Directory containing the item
     * @return void
     */
    public function deleteItem(string $item, string $dir): void
    {
        if (empty($item)) {
            $this->showMessage('error', 'Item name cannot be empty');
            return;
        }

        $itempath = $dir . '/' . basename($item);

        if (!$this->security->isPathSafe($itempath)) {
            $this->showMessage('error', 'Invalid path');
            return;
        }

        if (!file_exists($itempath)) {
            $this->showMessage('error', 'Item not found');
            return;
        }

        if (is_dir($itempath)) {
            if ($this->deleteDirectory($itempath)) {
                $this->showMessage('success', 'Directory deleted successfully', true);
            } else {
                $this->showMessage('error', 'Failed to delete directory');
            }
        } else {
            if (unlink($itempath)) {
                $this->showMessage('success', 'File deleted successfully', true);
            } else {
                $this->showMessage('error', 'Failed to delete file');
            }
        }
    }

    /**
     * Show JavaScript alert message
     *
     * @param string $type Message type (success, error, warning, info)
     * @param string $message Message text
     * @param bool $reload Whether to reload page after showing message
     * @return void
     */
    private function showMessage(string $type, string $message, bool $reload = false): void
    {
        $reloadScript = $reload ? '.then(() => window.location.reload())' : '';
        echo "<script>Swal.fire('" . ucfirst($type) . "', '" .
             addslashes($message) . "', '" . $type . "')" . $reloadScript . ";</script>";
    }

    /**
     * Recursively delete a directory
     *
     * @param string $dir Directory path to delete
     * @return bool True if deletion was successful
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $filepath = $dir . '/' . $file;
            if (is_dir($filepath)) {
                $this->deleteDirectory($filepath);
            } else {
                unlink($filepath);
            }
        }

        return rmdir($dir);
    }

    /**
     * Get directory listing with file information
     *
     * @param string $dir Directory path
     * @return array<string, array<int, array<string, mixed>>> Directory listing
     */
    public function getDirectoryListing(string $dir): array
    {
        $files = scandir($dir);
        $directories = [];
        $regularFiles = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filepath = $dir . '/' . $file;
            $fileInfo = [
                'name' => $file,
                'path' => $filepath,
                'size' => is_file($filepath) ? filesize($filepath) : 0,
                'modified' => filemtime($filepath),
                'type' => is_dir($filepath) ? 'directory' : 'file',
                'extension' => is_file($filepath) ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : '',
                'icon' => $this->getFileIcon($file)
            ];

            if (is_dir($filepath)) {
                $directories[] = $fileInfo;
            } else {
                $regularFiles[] = $fileInfo;
            }
        }

        // Sort arrays
        sort($directories);
        sort($regularFiles);

        return [
            'directories' => $directories,
            'files' => $regularFiles
        ];
    }

    /**
     * Get file icon emoji based on file type
     *
     * @param string $filename Filename
     * @return string File icon emoji
     */
    private function getFileIcon(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $icons = [
            'php' => '🐘',
            'html' => '🌐',
            'css' => '🎨',
            'js' => '⚡',
            'json' => '📋',
            'xml' => '📄',
            'txt' => '📝',
            'md' => '📖',
            'log' => '📋',
            'zip' => '📦',
            'rar' => '📦',
            'tar' => '📦',
            'gz' => '📦',
            'pdf' => '📕',
            'doc' => '📘',
            'docx' => '📘',
            'xls' => '📗',
            'xlsx' => '📗',
            'ppt' => '📙',
            'pptx' => '📙',
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'gif' => '🖼️',
            'svg' => '🖼️',
            'mp3' => '🎵',
            'wav' => '🎵',
            'mp4' => '🎬',
            'avi' => '🎬',
            'mov' => '🎬'
        ];

        return $icons[$extension] ?? '📄';
    }

    /**
     * Rename a file or directory
     *
     * @param string $oldName Current name
     * @param string $newName New name
     * @param string $dir Directory containing the item
     * @return void
     */
    public function renameItem(string $oldName, string $newName, string $dir): void
    {
        if (empty($oldName) || empty($newName)) {
            $this->showMessage('error', 'Name cannot be empty');
            return;
        }

        $oldPath = $dir . '/' . basename($oldName);
        $newName = $this->security->sanitizeFilename($newName);
        $newPath = $dir . '/' . basename($newName);

        if (!$this->security->isPathSafe($oldPath) || !$this->security->isPathSafe($newPath)) {
            $this->showMessage('error', 'Invalid path');
            return;
        }

        if (!file_exists($oldPath)) {
            $this->showMessage('error', 'Source item not found');
            return;
        }

        if (file_exists($newPath)) {
            $this->showMessage('error', 'Destination already exists');
            return;
        }

        if (rename($oldPath, $newPath)) {
            $this->showMessage('success', 'Item renamed successfully', true);
        } else {
            $this->showMessage('error', 'Rename failed');
        }
    }

    /**
     * Save file content
     *
     * @param string $filepath File path
     * @param string $content File content
     * @return void
     */
    public function saveFile(string $filepath, string $content): void
    {
        $realPath = realpath(dirname($filepath)) . '/' . basename($filepath);

        if (!$this->security->isPathSafe($realPath)) {
            $this->showMessage('error', 'Invalid file path');
            return;
        }

        if (file_put_contents($realPath, $content) !== false) {
            $redirectUrl = 'index.php?dir=' . urlencode(dirname($realPath));
            echo "<script>Swal.fire('Success', 'File saved successfully', 'success')" .
                 ".then(() => window.location.href='" . $redirectUrl . "');</script>";
        } else {
            $this->showMessage('error', 'Failed to save file');
        }
    }

    /**
     * Upload a file
     *
     * @param array<string, mixed> $file File upload data
     * @param string $dir Destination directory
     * @return void
     */
    public function uploadFile(array $file, string $dir): void
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $this->showMessage('error', 'Upload failed');
            return;
        }

        $filename = $this->security->sanitizeFilename($file['name']);
        $destination = $dir . '/' . $filename;

        if (!$this->security->isFileTypeAllowed($filename)) {
            $this->showMessage('error', 'File type not allowed');
            return;
        }

        if (!$this->security->isFileSizeAllowed($file['size'])) {
            $this->showMessage('error', 'File size exceeds limit');
            return;
        }

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $this->showMessage('success', 'File uploaded successfully', true);
        } else {
            $this->showMessage('error', 'Upload failed');
        }
    }

    /**
     * Copy an item to destination
     *
     * @param string $item Item name
     * @param string $destination Destination path
     * @param string $currentDir Current directory
     * @return void
     */
    public function copyItem(string $item, string $destination, string $currentDir): void
    {
        $sourcePath = $currentDir . '/' . basename($item);

        // Handle absolute paths starting with /
        if (strpos($destination, '/') === 0) {
            $destDir = $this->config->getRootPath() . $destination;
            // Create directory if it doesn't exist
            if (!$this->security->createDirectoryRecursive($destination)) {
                $this->showMessage('error', 'Failed to create destination directory');
                return;
            }
        } else {
            $destDir = $currentDir . '/' . trim($destination, '/');
        }

        if (!file_exists($sourcePath)) {
            $this->showMessage('error', 'Source item not found');
            return;
        }

        if (!is_dir($destDir)) {
            $this->showMessage('error', 'Destination directory not found');
            return;
        }

        $destPath = $destDir . '/' . basename($item);

        try {
            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
            $this->showMessage('success', 'Item copied successfully', true);
        } catch (Exception $e) {
            $this->showMessage('error', 'Copy failed: ' . $e->getMessage());
        }
    }

    /**
     * Move an item to destination
     *
     * @param string $item Item name
     * @param string $destination Destination path
     * @param string $currentDir Current directory
     * @return void
     */
    public function moveItem(string $item, string $destination, string $currentDir): void
    {
        $sourcePath = $currentDir . '/' . basename($item);

        // Handle absolute paths starting with /
        if (strpos($destination, '/') === 0) {
            $destDir = $this->config->getRootPath() . $destination;
            // Create directory if it doesn't exist
            if (!$this->security->createDirectoryRecursive($destination)) {
                $this->showMessage('error', 'Failed to create destination directory');
                return;
            }
        } else {
            $destDir = $currentDir . '/' . trim($destination, '/');
        }

        if (!file_exists($sourcePath)) {
            $this->showMessage('error', 'Source item not found');
            return;
        }

        if (!is_dir($destDir)) {
            $this->showMessage('error', 'Destination directory not found');
            return;
        }

        $destPath = $destDir . '/' . basename($item);

        if (rename($sourcePath, $destPath)) {
            $this->showMessage('success', 'Item moved successfully', true);
        } else {
            $this->showMessage('error', 'Move failed');
        }
    }

    /**
     * Recursively copy a directory
     *
     * @param string $source Source directory
     * @param string $dest Destination directory
     * @return void
     */
    private function copyDirectory(string $source, string $dest): void
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $files = scandir($source);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $sourcePath = $source . '/' . $file;
                $destPath = $dest . '/' . $file;

                if (is_dir($sourcePath)) {
                    $this->copyDirectory($sourcePath, $destPath);
                } else {
                    copy($sourcePath, $destPath);
                }
            }
        }
    }

    /**
     * Bulk delete multiple items
     *
     * @param array<int, string> $items Items to delete
     * @param string $dir Directory containing items
     * @return void
     */
    public function bulkDelete(array $items, string $dir): void
    {
        if (empty($items)) {
            $this->showMessage('error', 'No items selected');
            return;
        }

        $deletedCount = 0;
        foreach ($items as $item) {
            $itempath = $dir . '/' . basename($item);
            if (file_exists($itempath) && $this->security->isPathSafe($itempath)) {
                if (is_dir($itempath)) {
                    if ($this->deleteDirectory($itempath)) {
                        $deletedCount++;
                    }
                } else {
                    if (unlink($itempath)) {
                        $deletedCount++;
                    }
                }
            }
        }

        $this->showMessage('success', "$deletedCount item(s) deleted successfully", true);
    }

    /**
     * Bulk archive multiple items
     *
     * @param array<int, string> $items Items to archive
     * @param string $dir Directory containing items
     * @return void
     */
    public function bulkArchive(array $items, string $dir): void
    {
        if (empty($items)) {
            $this->showMessage('error', 'No items selected');
            return;
        }

        $archiveName = 'archive_' . date('Y-m-d_H-i-s') . '.zip';
        $archivePath = $dir . '/' . $archiveName;

        $zip = new ZipArchive();
        if ($zip->open($archivePath, ZipArchive::CREATE) !== true) {
            $this->showMessage('error', 'Failed to create archive');
            return;
        }

        $archivedCount = 0;
        foreach ($items as $item) {
            $itemPath = $dir . '/' . basename($item);
            if (file_exists($itemPath) && $this->security->isPathSafe($itemPath)) {
                if (is_file($itemPath)) {
                    $zip->addFile($itemPath, basename($item));
                    $archivedCount++;
                } elseif (is_dir($itemPath)) {
                    $this->addFilesToZip($zip, $itemPath, basename($item));
                    $archivedCount++;
                }
            }
        }

        $zip->close();

        if ($archivedCount > 0) {
            $this->downloadFile($archivePath);
            unlink($archivePath); // Clean up the temporary archive file
        } else {
            $this->showMessage('error', 'No valid items found to archive');
            unlink($archivePath); // Clean up empty archive
        }
    }

    /**
     * Bulk copy multiple items
     *
     * @param array<int, string> $items Items to copy
     * @param string $destination Destination path
     * @param string $currentDir Current directory
     * @return void
     */
    public function bulkCopy(array $items, string $destination, string $currentDir): void
    {
        if (empty($items)) {
            $this->showMessage('error', 'No items selected');
            return;
        }

        // Handle absolute paths starting with /
        if (strpos($destination, '/') === 0) {
            $destDir = $this->config->getRootPath() . $destination;
            // Create directory if it doesn't exist
            if (!$this->security->createDirectoryRecursive($destination)) {
                $this->showMessage('error', 'Failed to create destination directory');
                return;
            }
        } else {
            $destDir = $currentDir . '/' . trim($destination, '/');
        }

        if (!is_dir($destDir)) {
            $this->showMessage('error', 'Destination directory not found');
            return;
        }

        $copiedCount = 0;
        foreach ($items as $item) {
            $sourcePath = $currentDir . '/' . basename($item);
            $destPath = $destDir . '/' . basename($item);

            if (file_exists($sourcePath)) {
                try {
                    if (is_dir($sourcePath)) {
                        $this->copyDirectory($sourcePath, $destPath);
                    } else {
                        copy($sourcePath, $destPath);
                    }
                    $copiedCount++;
                } catch (Exception $e) {
                    // Continue with other items
                }
            }
        }

        $this->showMessage('success', "$copiedCount item(s) copied successfully", true);
    }

    /**
     * Bulk move multiple items
     *
     * @param array<int, string> $items Items to move
     * @param string $destination Destination path
     * @param string $currentDir Current directory
     * @return void
     */
    public function bulkMove(array $items, string $destination, string $currentDir): void
    {
        if (empty($items)) {
            $this->showMessage('error', 'No items selected');
            return;
        }

        // Handle absolute paths starting with /
        if (strpos($destination, '/') === 0) {
            $destDir = $this->config->getRootPath() . $destination;
            // Create directory if it doesn't exist
            if (!$this->security->createDirectoryRecursive($destination)) {
                $this->showMessage('error', 'Failed to create destination directory');
                return;
            }
        } else {
            $destDir = $currentDir . '/' . trim($destination, '/');
        }

        if (!is_dir($destDir)) {
            $this->showMessage('error', 'Destination directory not found');
            return;
        }

        $movedCount = 0;
        foreach ($items as $item) {
            $sourcePath = $currentDir . '/' . basename($item);
            $destPath = $destDir . '/' . basename($item);

            if (file_exists($sourcePath) && rename($sourcePath, $destPath)) {
                $movedCount++;
            }
        }

        $this->showMessage('success', "$movedCount item(s) moved successfully", true);
    }

    /**
     * Create archive with specified name
     *
     * @param string $archiveName Name of the archive
     * @param array<int, string> $items Items to archive
     * @param string $dir Current directory
     * @return void
     */
    public function createArchiveWithName(string $archiveName, array $items, string $dir): void
    {
        if (empty($items)) {
            $this->showMessage('error', 'No items selected for archiving');
            return;
        }

        $archiveName = $this->security->sanitizeFilename($archiveName);
        if (!str_ends_with($archiveName, '.zip')) {
            $archiveName .= '.zip';
        }

        $archivePath = $dir . '/' . $archiveName;

        $zip = new ZipArchive();
        if ($zip->open($archivePath, ZipArchive::CREATE) !== true) {
            $this->showMessage('error', 'Failed to create archive');
            return;
        }

        foreach ($items as $item) {
            $itemPath = $dir . '/' . basename($item);
            if (is_file($itemPath)) {
                $zip->addFile($itemPath, basename($item));
            } elseif (is_dir($itemPath)) {
                $this->addFilesToZip($zip, $itemPath, basename($item));
            }
        }

        $zip->close();
        $this->downloadFile($archivePath);
        unlink($archivePath);
    }

    /**
     * Unarchive file to current directory
     *
     * @param string $archiveFile Archive file to extract
     * @param string $dir Directory to extract to
     * @return void
     */
    public function unarchiveFile(string $archiveFile, string $dir): void
    {
        $archivePath = $dir . '/' . basename($archiveFile);

        if (!file_exists($archivePath)) {
            $this->showMessage('error', 'Archive file not found');
            return;
        }

        $extractDir = $dir . '/' . pathinfo($archiveFile, PATHINFO_FILENAME);

        try {
            if (str_ends_with(strtolower($archiveFile), '.zip')) {
                $zip = new ZipArchive();
                if ($zip->open($archivePath) === true) {
                    $zip->extractTo($extractDir);
                    $zip->close();
                    $this->showMessage('success', 'Archive extracted successfully', true);
                } else {
                    $this->showMessage('error', 'Failed to open ZIP archive');
                }
            } elseif (
                str_ends_with(strtolower($archiveFile), '.tar.gz') ||
                str_ends_with(strtolower($archiveFile), '.tgz')
            ) {
                $phar = new PharData($archivePath);
                $phar->extractTo($extractDir);
                $this->showMessage('success', 'Archive extracted successfully', true);
            } else {
                $this->showMessage('error', 'Unsupported archive format');
            }
        } catch (Exception $e) {
            $this->showMessage('error', 'Failed to extract archive: ' . $e->getMessage());
        }
    }

    /**
     * Unarchive file to specified path
     *
     * @param string $archiveFile Archive file to extract
     * @param string $destination Destination path
     * @param string $conflictResolution How to handle conflicts
     * @param string $currentDir Current directory
     * @return void
     */
    public function unarchiveToPath(
        string $archiveFile,
        string $destination,
        string $conflictResolution,
        string $currentDir
    ): void {
        $archivePath = $currentDir . '/' . basename($archiveFile);

        if (!file_exists($archivePath)) {
            $this->showMessage('error', 'Archive file not found');
            return;
        }

        // Handle absolute paths starting with /
        if (strpos($destination, '/') === 0) {
            $extractDir = $this->config->getRootPath() . $destination;
            // Create directory if it doesn't exist
            if (!$this->security->createDirectoryRecursive($destination)) {
                $this->showMessage('error', 'Failed to create destination directory');
                return;
            }
        } else {
            $extractDir = $currentDir . '/' . trim($destination, '/');
        }

        try {
            if (str_ends_with(strtolower($archiveFile), '.zip')) {
                $zip = new ZipArchive();
                if ($zip->open($archivePath) === true) {
                    $zip->extractTo($extractDir);
                    $zip->close();
                    $this->showMessage('success', 'Archive extracted successfully', true);
                } else {
                    $this->showMessage('error', 'Failed to open ZIP archive');
                }
            } elseif (
                str_ends_with(strtolower($archiveFile), '.tar.gz') ||
                str_ends_with(strtolower($archiveFile), '.tgz')
            ) {
                $phar = new PharData($archivePath);
                $phar->extractTo($extractDir);
                $this->showMessage('success', 'Archive extracted successfully', true);
            } else {
                $this->showMessage('error', 'Unsupported archive format');
            }
        } catch (Exception $e) {
            $this->showMessage('error', 'Failed to extract archive: ' . $e->getMessage());
        }
    }

    /**
     * Recursively add files to ZIP archive
     *
     * @param ZipArchive $zip ZIP archive instance
     * @param string $dir Directory to add
     * @param string $zipDir Directory path within ZIP
     * @return void
     */
    private function addFilesToZip(ZipArchive $zip, string $dir, string $zipDir = ''): void
    {
        $files = scandir($dir);
        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $dir . '/' . $file;
            $zipPath = $zipDir ? $zipDir . '/' . $file : $file;

            if (is_file($filePath)) {
                $zip->addFile($filePath, $zipPath);
            } elseif (is_dir($filePath)) {
                $zip->addEmptyDir($zipPath);
                $this->addFilesToZip($zip, $filePath, $zipPath);
            }
        }
    }

    /**
     * Show file editor
     *
     * @param string $file File to edit
     * @param string $dir Current directory
     * @return void
     */
    public function showFileEditor(string $file, string $dir): void
    {
        $filePath = $dir . '/' . basename($file);

        if (!file_exists($filePath) || !is_file($filePath)) {
            $this->showMessage('error', 'File not found');
            return;
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            $this->showMessage('error', 'Unable to read file');
            return;
        }

        // This would typically render an editor template
        // For now, we'll just output the content
        echo htmlspecialchars($content);
    }

    /**
     * Download a file
     *
     * @param string $filePath Full path to the file
     * @return void
     */
    public function downloadFile(string $filePath): void
    {
        if (!file_exists($filePath) || !is_file($filePath)) {
            $this->showMessage('error', 'File not found');
            return;
        }

        $fileName = basename($filePath);
        $fileSize = filesize($filePath);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . $fileSize);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($filePath);
        exit;
    }

    /**
     * Create archive of entire directory
     *
     * @param string $dir Directory to archive
     * @param string $archiveName Optional archive name
     * @return void
     */
    public function createArchive(string $dir, string $archiveName = ''): void
    {
        if (empty($archiveName)) {
            $archiveName = basename($dir) . '_' . date('Y-m-d_H-i-s') . '.zip';
        }

        $archivePath = dirname($dir) . '/' . $archiveName;

        $zip = new ZipArchive();
        if ($zip->open($archivePath, ZipArchive::CREATE) !== true) {
            $this->showMessage('error', 'Failed to create archive');
            return;
        }

        $this->addFilesToZip($zip, $dir, basename($dir));
        $zip->close();

        $this->downloadFile($archivePath);
        unlink($archivePath);
    }

    /**
     * Get path suggestions for autocomplete
     *
     * @param string $partialPath Partial path entered by user
     * @param string $currentDir Current directory
     * @return array<int, string> Array of path suggestions
     */
    public function getPathSuggestions(string $partialPath, string $currentDir): array
    {
        $suggestions = [];

        // Handle absolute paths starting with /
        if (strpos($partialPath, '/') === 0) {
            $searchDir = $this->config->getRootPath() . dirname($partialPath);
            $prefix = dirname($partialPath);
        } else {
            $searchDir = $currentDir . '/' . dirname($partialPath);
            $prefix = dirname($partialPath);
        }

        if (!is_dir($searchDir)) {
            return $suggestions;
        }

        $files = scandir($searchDir);
        if ($files === false) {
            return $suggestions;
        }

        $basename = basename($partialPath);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir($searchDir . '/' . $file) && str_starts_with($file, $basename)) {
                $fullPath = $prefix === '.' ? $file : $prefix . '/' . $file;
                $suggestions[] = $fullPath;
            }
        }

        return $suggestions;
    }
}
