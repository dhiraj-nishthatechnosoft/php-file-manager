# PHP File Manager - Demo Installation

This demonstrates how your package will be installed once published to Packagist.

## Quick Installation

```bash
# Install via Composer (after publishing to Packagist)
composer require dhiraj/php-file-manager

# The post-install script will automatically:
# - Create a public/ directory with index.php
# - Copy configuration file to filemanager-config.php
# - Set up all necessary assets
```

## Manual Setup Demo

```bash
# Create a new project directory
mkdir my-file-manager
cd my-file-manager

# Initialize Composer
composer init --name="mycompany/my-file-manager" --type=project --stability=stable --no-interaction

# Add the package
composer require dhiraj/php-file-manager

# Start PHP built-in server
php -S localhost:8080 -t public/
```

## Configuration

After installation, edit `filemanager-config.php` to customize:

```php
<?php
return [
    // Basic Settings
    'app_name' => 'My File Manager',
    'admin_username' => 'admin',
    'admin_password' => password_hash('admin123', PASSWORD_DEFAULT),
    
    // Directory Settings
    'root_directory' => __DIR__ . '/files',
    'upload_max_size' => '10M',
    'allowed_extensions' => ['txt', 'pdf', 'doc', 'docx', 'jpg', 'png', 'gif'],
    
    // Security Settings
    'session_timeout' => 1800, // 30 minutes
    'enable_file_editing' => true,
    'show_hidden_files' => false,
    
    // UI Settings
    'theme' => 'default',
    'files_per_page' => 50,
    'enable_thumbnails' => true,
];
```

## Usage in Your Application

```php
<?php
require_once 'vendor/autoload.php';

use Dhiraj\PhpFileManager\FileManager;
use Dhiraj\PhpFileManager\Config\Configuration;

// Load configuration
$config = new Configuration('filemanager-config.php');

// Initialize File Manager
$fileManager = new FileManager($config);

// Handle request
$fileManager->handleRequest();
```

## Features Available After Installation

✅ **Web Interface**: Modern, responsive file manager UI  
✅ **File Operations**: Upload, download, rename, delete, copy, move  
✅ **Bulk Operations**: Select multiple files for bulk actions  
✅ **Archive Support**: Create and extract ZIP files  
✅ **Text Editor**: Built-in editor for text files  
✅ **Image Preview**: Thumbnail generation and preview  
✅ **Security**: Authentication, session management, file validation  
✅ **Search**: Find files and folders quickly  
✅ **Permissions**: File permission management  
✅ **Responsive**: Works on desktop, tablet, and mobile  

## Directory Structure After Installation

```
your-project/
├── vendor/
│   └── dhiraj/
│       └── php-file-manager/
├── public/
│   └── index.php              # Entry point
├── files/                     # File storage (auto-created)
├── filemanager-config.php     # Configuration
└── composer.json
```

## Production Deployment

1. **Web Server Setup**: Point document root to `public/` directory
2. **Permissions**: Set appropriate permissions on `files/` directory
3. **Security**: Change default credentials in configuration
4. **SSL**: Enable HTTPS for secure file transfers
5. **Backup**: Set up regular backups of `files/` directory

## Package Statistics

- **Total Files**: 26 files
- **Source Code**: ~7,800 lines
- **Dependencies**: Minimal (only development dependencies)
- **PHP Version**: 8.0+
- **Standards**: PSR-12 compliant
- **Documentation**: Comprehensive guides included

## Support and Documentation

- 📖 **Installation Guide**: `INSTALL.md`
- 🚀 **Publishing Guide**: `PUBLISHING.md`  
- 🤝 **Contributing**: `CONTRIBUTING.md`
- 📋 **Changelog**: `CHANGELOG.md`
- 🐛 **Issues**: GitHub Issues
- 💬 **Discussions**: GitHub Discussions

---

**Ready to publish!** The package is now complete with all necessary files, documentation, and automation scripts for a professional Composer package release.
