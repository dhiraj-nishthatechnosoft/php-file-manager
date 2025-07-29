# PHP File Manager

[![Latest Version](https://img.shields.io/packagist/v/nishthatechnosoft/php-file-manager.svg)](https://packagist.org/packages/nishthatechnosoft/php-file-manager)
[![License](https://img.shields.io/packagist/l/nishthatechnosoft/php-file-manager.svg)](https://packagist.org/packages/nishthatechnosoft/php-file-manager)
[![PHP Version](https://img.shields.io/packagist/php-v/nishthatechnosoft/php-file-manager.svg)](https://packagist.org/packages/nishthatechnosoft/php-file-manager)

# PHP File Manager

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.0-blue)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](L## Sup## - Path-based na- PSR-12 co## Auth## Author

**Dhiraj Dhiman**
- GitHub: [@dhiraj-nishthatechnosoft](https://github.com/dhiraj-nishthatechnosoft)
- Email: dhiraj@nishthatechnosoft.com

---Dhiman**
- GitHub: [@dhiraj-nishthatechnosoft](https://github.com/dhiraj-nishthatechnosoft)
- Email: dhiraj@nishthatechnosoft.com

---

⭐ If you find this project useful, please consider giving it a star on GitHub!Comprehensive documentation

## Author

**Dhiraj Dhiman**
- GitHub: [@dhiraj-nishthatechnosoft](https://github.com/dhiraj-nishthatechnosoft)
- Email: dhiraj@nishthatechnosoft.com

---- Bulk operations support
- Archive handling (ZIP/TAR.GZ)
- Modern responsive UI
- PSR-12 compliant code
- Comprehensive documentation

## Author

**Dhiraj Dhiman**
- GitHub: [@dhiraj-nishthatechnosoft](https://github.com/dhiraj-nishthatechnosoft)
- Email: dhiraj@nishthatechnosoft.com

--- Dhiman**
- GitHub: [@dhiraj-nishthatechnosoft](https://github.com/dhiraj-nishthatechnosoft)
- Email: dhiraj@nishthatechnosoft.com

---

⭐ If you find this project useful, please consider giving it a star on GitHub! Dhiman**
- GitHub: [@dhiraj-nishthatechnosoft](https://github.com/dhiraj-nishthatechnosoft)
- Email: dhiraj@nishthatechnosoft.com

---

⭐ If you find this project useful, please consider giving it a star on GitHub!ocumentation](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/wiki)
- 🐛 [Issue Tracker](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/issues)
- 💬 [Discussions](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/discussions)[Documentation](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/wiki)
- 🐛 [Issue Tracker](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/issues)
- 💬 [Discussions](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/discussions)NSE)
[![PSR-12](https://img.shields.io/badge/code%20style-PSR--12-orange)](https://www.php-fig.org/psr/psr-12/)
[![Composer](https://img.shields.io/badge/composer-installable-brightgreen)](https://packagist.org/packages/nishthatechnosoft/php-file-manager)

A modern, feature-rich web-based file manager built with PHP 8.0+. This package provides a complete file management solution with advanced features like bulk operations, archive handling, path-based navigation, and a responsive user interface.

## ✨ Features

### 🔐 Security
- **Password-based authentication** with session management
- **Path traversal protection** preventing unauthorized access
- **File type validation** with configurable allowed extensions
- **File size limits** to prevent abuse
- **CSRF protection** and secure session handling

### 📁 File Operations
- **Create, edit, rename, and delete** files and directories
- **Upload files** with drag-and-drop support
- **Download files** and directories
- **Copy and move** items between directories
- **Bulk operations** for multiple files/folders

### 📦 Archive Management
- **Create archives** (ZIP format) from selected files/folders
- **Extract archives** (ZIP, TAR.GZ, TGZ) to current or custom directory
- **Bulk archive creation** for multiple items
- **Archive preview** before extraction

### 🎨 Modern Interface
- **Responsive design** works on desktop and mobile
- **Path-based navigation** with autocomplete suggestions
- **File type icons** with visual file identification
- **Drag-and-drop** file uploads
- **Real-time feedback** with SweetAlert2 notifications

### ⚡ Advanced Features
- **Path autocomplete** for quick navigation
- **File size formatting** (B, KB, MB, GB)
- **Last modified timestamps**
- **Directory breadcrumbs**
- **Keyboard shortcuts** for common actions

## 📦 Installation

### Via Composer (Recommended)

```bash
composer require nishthatechnosoft/php-file-manager
```

### Manual Installation

1. Download the latest release
2. Extract to your project directory
3. Install dependencies: `composer install`

## 🚀 Quick Start

### Basic Usage

```php
<?php
require_once 'vendor/autoload.php';

use Dhiraj\PhpFileManager\FileManager;

// Basic configuration
$config = [
    'root_path' => __DIR__ . '/files',
    'password' => 'your-secure-password',
    'max_file_size' => 50 * 1024 * 1024, // 50MB
];

// Initialize and run
$fileManager = new FileManager($config);
$fileManager->run();
```

### Using Configuration File

Create `filemanager-config.php`:

```php
<?php
return [
    'root_path' => '/path/to/files',
    'password' => 'secure-password-123',
    'max_file_size' => 100 * 1024 * 1024, // 100MB
    'allowed_extensions' => [
        'txt', 'pdf', 'jpg', 'png', 'zip', 'doc', 'xls'
    ],
    'enable_bulk_operations' => true,
    'enable_archive_operations' => true,
];
```

Then in your PHP file:

```php
<?php
require_once 'vendor/autoload.php';

use Dhiraj\PhpFileManager\FileManager;

$config = require 'filemanager-config.php';
$fileManager = new FileManager($config);
$fileManager->run();
```

## ⚙️ Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `root_path` | string | `$_SERVER['DOCUMENT_ROOT']` | Root directory for file operations |
| `upload_path` | string | `{root_path}/uploads` | Directory for file uploads |
| `password` | string | `'admin123'` | Authentication password |
| `max_file_size` | integer | `52428800` (50MB) | Maximum file upload size in bytes |
| `allowed_extensions` | array | See below | Array of allowed file extensions |
| `session_name` | string | `'php_file_manager'` | Session name for authentication |
| `enable_compression` | boolean | `true` | Enable file compression features |
| `enable_bulk_operations` | boolean | `true` | Enable bulk file operations |
| `enable_archive_operations` | boolean | `true` | Enable archive creation/extraction |

### Default Allowed Extensions

```php
[
    'txt', 'php', 'html', 'css', 'js', 'json', 'xml', 'md', 'log',
    'zip', 'tar', 'gz', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 
    'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'mp3', 
    'wav', 'mp4', 'avi', 'mov'
]
```

## 🎯 Usage Examples

### Custom Authentication

```php
$fileManager = new FileManager([
    'password' => hash('sha256', 'my-secret-password'),
    'session_name' => 'my_app_filemanager',
]);
```

### Restricted File Types

```php
$fileManager = new FileManager([
    'allowed_extensions' => ['txt', 'pdf', 'jpg', 'png'],
    'max_file_size' => 10 * 1024 * 1024, // 10MB limit
]);
```

### Production Configuration

```php
$fileManager = new FileManager([
    'root_path' => '/var/www/uploads',
    'password' => $_ENV['FILEMANAGER_PASSWORD'],
    'max_file_size' => 200 * 1024 * 1024, // 200MB
    'allowed_extensions' => [
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 
        'jpg', 'jpeg', 'png', 'gif'
    ],
]);
```

## 🛡️ Security Considerations

1. **Change Default Password**: Always use a strong, unique password
2. **Restrict File Types**: Only allow necessary file extensions
3. **Set Upload Limits**: Configure appropriate file size limits
4. **Use HTTPS**: Always serve over encrypted connections
5. **Restrict Access**: Use `.htaccess` or server config to limit access
6. **Regular Updates**: Keep the package updated for security patches

### Example .htaccess Protection

```apache
# Restrict access to file manager
<Files "filemanager.php">
    Require ip 192.168.1.0/24
    # Or require authentication
    # AuthType Basic
    # AuthName "File Manager"
    # AuthUserFile /path/to/.htpasswd
    # Require valid-user
</Files>
```

## 🔧 Advanced Usage

### Custom Resource Management

```php
use Dhiraj\PhpFileManager\FileManager;
use Dhiraj\PhpFileManager\Resources\ResourceManager;

$fileManager = new FileManager($config);
$resourceManager = $fileManager->getResourceManager();

// Get CSS content for custom styling
$css = $resourceManager->getCssContent();

// Get JavaScript for custom integration
$js = $resourceManager->getJsContent();
```

### Integration with Frameworks

#### Laravel Integration

```php
// In a Laravel controller
public function fileManager()
{
    $config = config('filemanager');
    $fileManager = new \Dhiraj\PhpFileManager\FileManager($config);
    
    ob_start();
    $fileManager->run();
    $output = ob_get_clean();
    
    return response($output);
}
```

#### Symfony Integration

```php
// In a Symfony controller
use Dhiraj\PhpFileManager\FileManager;
use Symfony\Component\HttpFoundation\Response;

public function fileManager(): Response
{
    $config = $this->getParameter('filemanager');
    $fileManager = new FileManager($config);
    
    ob_start();
    $fileManager->run();
    $content = ob_get_clean();
    
    return new Response($content);
}
```

## 🎨 Customization

### Custom Styling

Override the default CSS by including your own stylesheet after the package CSS:

```html
<link rel="stylesheet" href="path/to/filemanager-styles.css">
<link rel="stylesheet" href="path/to/your-custom.css">
```

### Custom JavaScript

Extend functionality by adding your own JavaScript:

```html
<script src="path/to/filemanager-script.js"></script>
<script>
// Your custom JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Custom functionality here
});
</script>
```

## 🧪 Development

### Requirements

- PHP 8.0 or higher
- Composer
- Extensions: zip, phar, json, session

### Setup Development Environment

```bash
git clone https://github.com/dhiraj-nishthatechnosoft/php-file-manager.git
cd php-file-manager
composer install
```

### Running Tests

```bash
composer test
```

### Code Style

```bash
# Check code style
composer phpcs

# Fix code style
composer fix-cs

# Static analysis
composer phpstan
```

## 📝 Changelog

### Version 1.0.0
- Initial release
- Complete file management functionality
- PSR-12 compliant code
- Composer package structure
- Comprehensive documentation

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Contribution Guidelines

- Follow PSR-12 coding standards
- Add tests for new functionality
- Update documentation as needed
- Ensure backward compatibility

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Built with modern PHP practices
- Uses SweetAlert2 for notifications
- Inspired by popular file managers
- Community feedback and contributions

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/issues)
- **Documentation**: [GitHub Wiki](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/wiki)
- **Discussions**: [GitHub Discussions](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/discussions)

## 🔗 Links

- [Packagist Package](https://packagist.org/packages/nishthatechnosoft/php-file-manager)
- [GitHub Repository](https://github.com/dhiraj-nishthatechnosoft/php-file-manager)
- [Documentation](https://github.com/dhiraj-nishthatechnosoft/php-file-manager/wiki)

---

**Made with ❤️ by [Dhiraj Dhiman](https://github.com/dhiraj-nishthatechnosoft)**

## Features

- 🔐 **Secure Authentication** - Password-protected access with session management
- 📁 **File & Folder Operations** - Create, rename, delete, copy, and move files/folders
- 📦 **Archive Support** - Create and extract ZIP/TAR.GZ archives
- � **Bulk Operations** - Select and operate on multiple files simultaneously  
- 🛤️ **Path-Based Navigation** - Direct path input with auto-suggestions
- 📝 **File Editor** - Built-in text editor for code files
- ⬆️ **File Upload** - Drag and drop file uploads
- 🎨 **Modern UI** - Clean, responsive interface with dark mode support
- 🔍 **Search & Filter** - Quick file search and filtering
- 📊 **File Information** - File sizes, modification dates, and permissions

## Installation

### Via Composer (Recommended)

```bash
composer require nishthatechnosoft/php-file-manager
```

### Manual Installation

1. Download the latest release from GitHub
2. Extract files to your web directory
3. Install dependencies:
   ```bash
   composer install
   ```

## Quick Start

### Basic Usage

```php
<?php
require_once 'vendor/autoload.php';

use Dhiraj\PhpFileManager\FileManager;

// Create file manager instance
$fileManager = new FileManager([
    'root_path' => '/path/to/your/files',
    'password' => 'your-secure-password'
]);

// Run the application
$fileManager->run();
```

### Advanced Configuration

```php
<?php
require_once 'vendor/autoload.php';

use Dhiraj\PhpFileManager\FileManager;

$config = [
    'root_path' => '/var/www/files',
    'upload_path' => '/var/www/uploads',
    'max_file_size' => 100 * 1024 * 1024, // 100MB
    'allowed_extensions' => ['txt', 'pdf', 'jpg', 'png', 'zip'],
    'password' => 'super-secure-password',
    'session_name' => 'my_file_manager',
    'enable_compression' => true,
    'enable_bulk_operations' => true,
    'enable_archive_operations' => true
];

$fileManager = new FileManager($config);
$fileManager->run();
```

## Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `root_path` | string | `$_SERVER['DOCUMENT_ROOT']` | Root directory for file operations |
| `upload_path` | string | `{root_path}/uploads` | Directory for uploaded files |
| `max_file_size` | int | `52428800` (50MB) | Maximum file upload size in bytes |
| `allowed_extensions` | array | See config | Array of allowed file extensions |
| `password` | string | `admin123` | Authentication password |
| `session_name` | string | `php_file_manager` | Session variable name |
| `enable_compression` | bool | `true` | Enable/disable compression features |
| `enable_bulk_operations` | bool | `true` | Enable/disable bulk operations |
| `enable_archive_operations` | bool | `true` | Enable/disable archive operations |

## Security Features

- **Path Validation** - Prevents directory traversal attacks
- **File Type Filtering** - Configurable allowed file extensions
- **Size Limits** - Configurable file size restrictions
- **Session Management** - Secure session handling with timeouts
- **Input Sanitization** - All user inputs are sanitized
- **CSRF Protection** - Cross-Site Request Forgery protection

## API Reference

### FileManager Class

#### Constructor
```php
public function __construct(array $config = [])
```

#### Methods
```php
public function run(): void                              // Run the application
public function getConfig(): Configuration               // Get configuration instance
public function getAuth(): AuthenticationService        // Get auth service
public function getSecurity(): SecurityService          // Get security service
public function getFileController(): FileController     // Get file controller
```

### Configuration Class

#### Methods
```php
public function get(string $key, mixed $default = null): mixed
public function set(string $key, mixed $value): void
public function getRootPath(): string
public function getMaxFileSize(): int
public function getAllowedExtensions(): array
public function isExtensionAllowed(string $filename): bool
```

### FileController Class

#### File Operations
```php
public function createFile(string $filename, string $dir): void
public function createFolder(string $foldername, string $dir): void
public function deleteItem(string $item, string $dir): void
public function renameItem(string $oldName, string $newName, string $dir): void
public function copyItem(string $item, string $destination, string $currentDir): void
public function moveItem(string $item, string $destination, string $currentDir): void
```

#### Bulk Operations
```php
public function bulkDelete(array $items, string $dir): void
public function bulkCopy(array $items, string $destination, string $currentDir): void
public function bulkMove(array $items, string $destination, string $currentDir): void
```

#### Archive Operations
```php
public function createArchiveWithName(string $archiveName, array $items, string $dir): void
public function unarchiveFile(string $archiveFile, string $dir): void
public function unarchiveToPath(string $archiveFile, string $destination, string $conflictResolution, string $currentDir): void
```

## Frontend Features

### Path-Based Navigation
- Direct path input with `/` prefix for absolute paths
- Auto-suggestions while typing paths
- Keyboard navigation (arrow keys, enter)
- Automatic directory creation

### Bulk Operations
- Multi-select with checkboxes
- Keyboard shortcuts (Ctrl+A for select all)
- Bulk copy, move, delete, and archive operations

### File Editor
- Syntax highlighting for common file types
- Auto-save functionality
- Full-screen editing mode

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 11+
- Edge 16+

## Requirements

- PHP 8.0 or higher
- PHP Extensions: `zip`, `phar`, `json`
- Web server (Apache, Nginx, etc.)

## Development

### Running Tests
```bash
composer test
```

### Code Style Check
```bash
composer phpcs
```

### Static Analysis
```bash
composer phpstan
```

### Fix Code Style
```bash
composer fix-cs
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Changelog

### v1.0.0 (2025-01-29)
- Initial release
- Complete file management functionality
- Path-based navigation system
- Bulk operations support
- Archive handling (ZIP/TAR.GZ)
- Modern responsive UI
- PSR-12 compliant code
- Comprehensive documentation

## Support


## Author

**Dhiraj Dhiman**
- GitHub: [@dhiraj-nishthatechnosoft](https://github.com/dhiraj-nishthatechnosoft)
- Email: dhiraj@nishthatechnosoft.com

---

⭐ If you find this project useful, please consider giving it a star on GitHub!
- 📖 Markdown files
- 📦 Archive files
- 🖼️ Image files
- 🎵 Audio files
- 🎬 Video files
- 📄 Other files

## Security Considerations

1. **Change Default Password**: Always change the default password in production
2. **Restrict Access**: Consider additional IP-based restrictions
3. **File Permissions**: Set appropriate file system permissions
4. **HTTPS**: Use HTTPS in production environments
5. **File Type Validation**: The system restricts editable file types for security

## Requirements

- PHP 5.6 or higher
- Web server (Apache, Nginx, etc.)
- ZipArchive extension for archive functionality
- Write permissions on the target directory

## Troubleshooting

### Common Issues

1. **Permission Denied**: Ensure web server has write permissions
2. **Upload Fails**: Check `upload_max_filesize` and `post_max_size` in php.ini
3. **Archive Creation Fails**: Ensure ZipArchive extension is installed
4. **Can't Edit Files**: Check if file extension is in ALLOWED_EXTENSIONS array

### Error Messages

- **"File not found"**: File doesn't exist or was moved
- **"Permission denied"**: Insufficient file system permissions
- **"Upload failed"**: File too large or upload directory not writable
- **"Archive creation failed"**: ZipArchive extension not available

## License

This project is open source and available under the MIT License.

## Contributing

Feel free to submit issues, fork the repository, and create pull requests for any improvements.

## Changelog

### Version 1.0
- Initial release
- Basic file management operations
- Archive creation functionality  
- Responsive web interface
- Security features
