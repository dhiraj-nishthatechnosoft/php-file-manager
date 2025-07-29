<?php

declare(strict_types=1);

namespace NishthaTechnosoft\PhpFileManager;

use Composer\Script\Event;
use Composer\IO\IOInterface;

/**
 * Composer installer class for post-install tasks
 *
 * This class handles post-install and post-update tasks for the
 * PHP File Manager package, including asset publishing and setup.
 *
 * @package Dhiraj\PhpFileManager
 * @author Dhiraj Dhiman <dhiraj@nishthatechnosoft.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class Installer
{
    /**
     * Post-install script
     *
     * @param Event $event Composer event
     * @return void
     */
    public static function postInstall(Event $event): void
    {
        $io = $event->getIO();
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $packageDir = dirname($vendorDir) . '/vendor/dhiraj/php-file-manager';

        $io->write('<info>Setting up dhiraj/php-file-manager...</info>');

        self::publishAssets($io, $packageDir);
        self::createSampleConfig($io, dirname($vendorDir));
        self::displayInstructions($io);
    }

    /**
     * Post-update script
     *
     * @param Event $event Composer event
     * @return void
     */
    public static function postUpdate(Event $event): void
    {
        $io = $event->getIO();
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $packageDir = dirname($vendorDir) . '/vendor/dhiraj/php-file-manager';

        $io->write('<info>Updating dhiraj/php-file-manager...</info>');

        self::publishAssets($io, $packageDir);
        $io->write('<comment>Package updated successfully!</comment>');
    }

    /**
     * Publish assets to public directory
     *
     * @param IOInterface $io IO interface
     * @param string $packageDir Package directory
     * @return void
     */
    private static function publishAssets(IOInterface $io, string $packageDir): void
    {
        $publicDir = dirname($packageDir, 2) . '/public';
        $assetsDir = $packageDir . '/assets';

        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
            $io->write('<info>Created public directory</info>');
        }

        // Copy CSS files
        if (file_exists($packageDir . '/assets/styles.css')) {
            copy($packageDir . '/assets/styles.css', $publicDir . '/filemanager-styles.css');
            $io->write('<info>Published CSS assets</info>');
        }

        // Copy JS files
        if (file_exists($packageDir . '/assets/script.js')) {
            copy($packageDir . '/assets/script.js', $publicDir . '/filemanager-script.js');
            $io->write('<info>Published JavaScript assets</info>');
        }

        // Copy sample index.php
        if (file_exists($packageDir . '/public/index.php')) {
            if (!file_exists($publicDir . '/filemanager.php')) {
                copy($packageDir . '/public/index.php', $publicDir . '/filemanager.php');
                $io->write('<info>Published sample filemanager.php</info>');
            }
        }
    }

    /**
     * Create sample configuration file
     *
     * @param IOInterface $io IO interface
     * @param string $rootDir Root directory
     * @return void
     */
    private static function createSampleConfig(IOInterface $io, string $rootDir): void
    {
        $configFile = $rootDir . '/filemanager-config.php';

        if (!file_exists($configFile)) {
            $configContent = <<<'PHP'
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
PHP;

            file_put_contents($configFile, $configContent);
            $io->write('<info>Created sample configuration file: filemanager-config.php</info>');
        }
    }

    /**
     * Display post-install instructions
     *
     * @param IOInterface $io IO interface
     * @return void
     */
    private static function displayInstructions(IOInterface $io): void
    {
        $instructions = <<<'INSTRUCTIONS'

<comment>🎉 dhiraj/php-file-manager has been installed successfully!</comment>

<info>Quick Start:</info>
1. Navigate to your web directory: <comment>cd public/</comment>
2. Access the file manager: <comment>http://your-domain.com/filemanager.php</comment>
3. Login with password: <comment>admin123</comment>

<info>Configuration:</info>
- Edit <comment>filemanager-config.php</comment> to customize settings
- Change the default password for security
- Adjust file size limits and allowed extensions

<info>Integration:</info>
```php
<?php
require_once 'vendor/autoload.php';

use NishthaTechnosoft\PhpFileManager\FileManager;

// Load configuration
$config = require 'filemanager-config.php';

// Initialize and run file manager
$fileManager = new FileManager($config);
$fileManager->run();
```

<info>Documentation:</info>
- GitHub: <comment>https://github.com/dhiraj-nishthatechnosoft/php-file-manager</comment>
- Issues: <comment>https://github.com/dhiraj-nishthatechnosoft/php-file-manager/issues</comment>

<comment>Happy file managing! 🚀</comment>

INSTRUCTIONS;

        $io->write($instructions);
    }
}
