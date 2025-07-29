# Installation Guide

This guide will help you install and set up the PHP File Manager package.

## Prerequisites

- PHP 8.0 or higher
- Composer
- Web server (Apache, Nginx, etc.)
- PHP Extensions: `zip`, `phar`, `json`

## Installation Methods

### Method 1: Via Composer (Recommended)

```bash
# Create a new project directory
mkdir my-file-manager
cd my-file-manager

# Install the package
composer require dhiraj/php-file-manager

# Create a basic index.php file
cat > index.php << 'EOF'
<?php
require_once 'vendor/autoload.php';

use Dhiraj\PhpFileManager\FileManager;

$fileManager = new FileManager([
    'root_path' => __DIR__ . '/files',
    'password' => 'your-secure-password'
]);

$fileManager->run();
EOF

# Create a files directory
mkdir files
```

### Method 2: Manual Installation

```bash
# Clone or download the repository
git clone https://github.com/dhirajdhiman/php-file-manager.git
cd php-file-manager

# Install dependencies
composer install

# Set up web server to point to public/index.php
```

## Configuration

### Basic Configuration

Create an `index.php` file with your configuration:

```php
<?php
require_once 'vendor/autoload.php';

use Dhiraj\PhpFileManager\FileManager;

$config = [
    'root_path' => '/path/to/your/files',
    'password' => 'your-secure-password',
    'max_file_size' => 100 * 1024 * 1024, // 100MB
    'allowed_extensions' => ['txt', 'pdf', 'jpg', 'png', 'zip'],
];

$fileManager = new FileManager($config);
$fileManager->run();
```

### Advanced Configuration

```php
<?php
require_once 'vendor/autoload.php';

use Dhiraj\PhpFileManager\FileManager;

$config = [
    // Basic settings
    'root_path' => $_SERVER['DOCUMENT_ROOT'] . '/files',
    'upload_path' => $_SERVER['DOCUMENT_ROOT'] . '/uploads',
    'password' => 'super-secure-password',
    'session_name' => 'my_file_manager',
    
    // File restrictions
    'max_file_size' => 50 * 1024 * 1024, // 50MB
    'allowed_extensions' => [
        // Text files
        'txt', 'md', 'json', 'xml', 'csv',
        
        // Web files
        'html', 'css', 'js', 'php',
        
        // Images
        'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico',
        
        // Documents
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
        
        // Archives
        'zip', 'tar', 'gz', 'rar', '7z'
    ],
    
    // Feature toggles
    'enable_compression' => true,
    'enable_bulk_operations' => true,
    'enable_archive_operations' => true,
];

$fileManager = new FileManager($config);
$fileManager->run();
```

### Environment Variables

You can also use environment variables for configuration:

```bash
# Set environment variables
export FM_ROOT_PATH="/var/www/files"
export FM_PASSWORD="your-secure-password"
export FM_MAX_FILE_SIZE="104857600"  # 100MB in bytes
```

Then in your PHP file:

```php
<?php
require_once 'vendor/autoload.php';

use Dhiraj\PhpFileManager\FileManager;

$config = [
    'root_path' => $_ENV['FM_ROOT_PATH'] ?? __DIR__ . '/files',
    'password' => $_ENV['FM_PASSWORD'] ?? 'admin123',
    'max_file_size' => (int)($_ENV['FM_MAX_FILE_SIZE'] ?? 52428800),
];

$fileManager = new FileManager($config);
$fileManager->run();
```

## Web Server Configuration

### Apache

Create a `.htaccess` file in your document root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>
```

### Nginx

Add this to your Nginx server configuration:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/your/project;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Security headers
    add_header X-Content-Type-Options nosniff;
    add_header X-Frame-Options DENY;
    add_header X-XSS-Protection "1; mode=block";
}
```

## Security Setup

### File Permissions

Set appropriate file permissions:

```bash
# Make sure the web server can read/write to necessary directories
chmod 755 /path/to/your/files
chown -R www-data:www-data /path/to/your/files

# Secure the application files
chmod 644 index.php
chmod -R 644 vendor/
```

### Change Default Password

⚠️ **Important**: Always change the default password before deploying to production!

```php
$config = [
    'password' => 'your-very-secure-password-here',
    // ... other config
];
```

### Additional Security

1. **Use HTTPS**: Always use SSL/TLS in production
2. **Restrict Access**: Use `.htaccess` or server config to restrict access by IP
3. **Regular Updates**: Keep the package and dependencies updated
4. **File Type Restrictions**: Only allow necessary file extensions

## Troubleshooting

### Common Issues

1. **Permission Denied Errors**
   ```bash
   chmod 755 /path/to/files
   chown -R www-data:www-data /path/to/files
   ```

2. **File Upload Issues**
   Check PHP configuration:
   ```ini
   upload_max_filesize = 50M
   post_max_size = 50M
   max_execution_time = 300
   memory_limit = 256M
   ```

3. **Archive Operations Not Working**
   Ensure PHP extensions are installed:
   ```bash
   php -m | grep -E "(zip|phar)"
   ```

4. **Session Issues**
   Check that session directory is writable:
   ```bash
   ls -la /tmp/
   ```

### Debug Mode

Enable debug mode for troubleshooting:

```php
<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'vendor/autoload.php';
// ... rest of your code
```

## Testing the Installation

1. Navigate to your file manager URL
2. Enter your password
3. Try creating a folder
4. Upload a test file
5. Test archive creation
6. Test bulk operations

## Next Steps

- Read the [README.md](README.md) for detailed usage instructions
- Check the [API Reference](README.md#api-reference) for customization options
- Review the security features and best practices
- Consider setting up automated backups of managed files

## Support

If you encounter issues:

1. Check the [troubleshooting section](#troubleshooting) above
2. Review the [GitHub Issues](https://github.com/dhirajdhiman/php-file-manager/issues)
3. Create a new issue with detailed information about your setup and the problem
