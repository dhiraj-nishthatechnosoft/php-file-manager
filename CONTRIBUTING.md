# Contributing to PHP File Manager

Thank you for your interest in contributing to PHP File Manager! This document provides guidelines and information for contributors.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Environment](#development-environment)
- [Making Changes](#making-changes)
- [Testing](#testing)
- [Code Style](#code-style)
- [Submitting Changes](#submitting-changes)
- [Reporting Issues](#reporting-issues)
- [Feature Requests](#feature-requests)

## Code of Conduct

This project adheres to a [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to the project maintainers.

## Getting Started

### Prerequisites

- PHP 8.0 or higher
- Composer
- Git
- A web server (Apache, Nginx, or PHP built-in server)

### Fork and Clone

1. Fork the repository on GitHub
2. Clone your fork locally:
   ```bash
   git clone https://github.com/dhiraj-nishthatechnosoft/php-file-manager.git
   cd php-file-manager
   ```

3. Add the upstream remote:
   ```bash
   git remote add upstream https://github.com/dhiraj-nishthatechnosoft/php-file-manager.git
   ```

## Development Environment

### Setup

1. Install dependencies:
   ```bash
   composer install
   ```

2. Copy the example configuration:
   ```bash
   cp config.example.php config.php
   ```

3. Start the development server:
   ```bash
   php -S localhost:8000 -t public/
   ```

4. Access the application at `http://localhost:8000`

### Directory Structure

```
php-file-manager/
├── src/                    # Source code
│   ├── Config/            # Configuration classes
│   ├── Controllers/       # Controllers
│   ├── Resources/         # Resource management
│   └── Services/          # Service classes
├── assets/                # CSS, JS, and other assets
├── views/                 # View templates
├── public/                # Web-accessible files
├── tests/                 # Test files
├── docs/                  # Documentation
└── vendor/                # Composer dependencies
```

## Making Changes

### Branch Naming

Use descriptive branch names:
- `feature/add-user-management`
- `bugfix/fix-upload-error`
- `security/fix-path-traversal`
- `docs/update-readme`

### Commit Messages

Follow conventional commit format:
```
type(scope): description

[optional body]

[optional footer]
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or modifying tests
- `chore`: Maintenance tasks

Examples:
```
feat(upload): add drag-and-drop support
fix(security): prevent path traversal in file operations
docs(readme): update installation instructions
```

## Testing

### Running Tests

```bash
# Run all tests
composer test

# Run specific test suite
composer test -- --testsuite=unit

# Run tests with coverage
composer test -- --coverage-html coverage/
```

### Writing Tests

- Place tests in the `tests/` directory
- Follow the same namespace structure as `src/`
- Use descriptive test method names
- Test both positive and negative cases
- Mock external dependencies

Example test:
```php
<?php

namespace Dhiraj\PhpFileManager\Tests\Services;

use PHPUnit\Framework\TestCase;
use Dhiraj\PhpFileManager\Services\SecurityService;

class SecurityServiceTest extends TestCase
{
    public function testPathTraversalPrevention(): void
    {
        $security = new SecurityService();
        
        $this->assertFalse($security->isPathSafe('../etc/passwd'));
        $this->assertFalse($security->isPathSafe('../../secret'));
        $this->assertTrue($security->isPathSafe('/var/www/uploads/file.txt'));
    }
}
```

## Code Style

This project follows PSR-12 coding standards.

### Checking Code Style

```bash
# Check for style violations
composer phpcs

# Automatically fix style issues
composer fix-cs

# Run static analysis
composer phpstan
```

### Style Guidelines

- Use strict typing: `declare(strict_types=1);`
- Use meaningful variable and method names
- Add proper docblocks for all public methods
- Keep methods small and focused
- Use early returns to reduce nesting
- Add type hints for all parameters and return values

### Example Code Style

```php
<?php

declare(strict_types=1);

namespace Dhiraj\PhpFileManager\Services;

/**
 * Example service class
 */
class ExampleService
{
    /**
     * Process a file with validation
     *
     * @param string $filename File to process
     * @param array<string, mixed> $options Processing options
     * @return bool True if successful
     * @throws \InvalidArgumentException If filename is invalid
     */
    public function processFile(string $filename, array $options = []): bool
    {
        if (empty($filename)) {
            throw new \InvalidArgumentException('Filename cannot be empty');
        }

        // Early return for invalid files
        if (!$this->isValidFile($filename)) {
            return false;
        }

        // Process the file
        return $this->doProcessing($filename, $options);
    }

    /**
     * Check if file is valid
     */
    private function isValidFile(string $filename): bool
    {
        return file_exists($filename) && is_readable($filename);
    }
}
```

## Submitting Changes

### Pull Request Process

1. Update your fork:
   ```bash
   git fetch upstream
   git checkout main
   git merge upstream/main
   ```

2. Create a feature branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. Make your changes and commit them:
   ```bash
   git add .
   git commit -m "feat: add your feature"
   ```

4. Push to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```

5. Create a pull request on GitHub

### Pull Request Guidelines

- Fill out the pull request template
- Include a clear description of changes
- Reference any related issues
- Ensure all tests pass
- Update documentation if needed
- Add tests for new functionality

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] Added tests for new functionality
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No new warnings introduced
```

## Reporting Issues

### Bug Reports

When reporting bugs, include:

- **Environment**: PHP version, OS, web server
- **Steps to reproduce**: Clear, numbered steps
- **Expected behavior**: What should happen
- **Actual behavior**: What actually happens
- **Error messages**: Full error text or logs
- **Screenshots**: If applicable

### Issue Template

```markdown
**Environment:**
- PHP Version: 8.1.0
- OS: Ubuntu 20.04
- Web Server: Apache 2.4

**Steps to Reproduce:**
1. Navigate to file manager
2. Select multiple files
3. Click "Archive Selected"
4. Error occurs

**Expected Behavior:**
Files should be archived successfully

**Actual Behavior:**
Error message "Failed to create archive" appears

**Error Messages:**
```
PHP Fatal Error: Uncaught exception...
```

**Additional Context:**
Any other relevant information
```

## Feature Requests

When requesting features:

- Describe the problem you're trying to solve
- Explain why this feature would be useful
- Provide examples of how it would work
- Consider alternative solutions
- Be open to discussion and feedback

### Feature Request Template

```markdown
**Problem Statement:**
Description of the problem or limitation

**Proposed Solution:**
Detailed description of your proposed feature

**Alternative Solutions:**
Other ways this could be addressed

**Use Cases:**
Real-world scenarios where this would be helpful

**Additional Context:**
Any other relevant information, mockups, or examples
```

## Development Guidelines

### Security Considerations

- Always validate user input
- Use parameterized queries for database operations
- Implement proper authentication and authorization
- Follow security best practices for file operations
- Sanitize file paths and names
- Validate file types and sizes

### Performance Guidelines

- Avoid loading unnecessary data
- Use appropriate caching strategies
- Optimize database queries
- Consider memory usage for large files
- Implement proper error handling

### Documentation

- Update README.md for user-facing changes
- Add inline comments for complex logic
- Update API documentation
- Include examples in docblocks
- Keep changelog updated

## Community

### Communication Channels

- **GitHub Issues**: Bug reports and feature requests
- **GitHub Discussions**: General questions and community discussion
- **Pull Requests**: Code contributions and reviews

### Getting Help

If you need help with contributing:

1. Check existing issues and discussions
2. Read the documentation thoroughly
3. Ask questions in GitHub Discussions
4. Reach out to maintainers if needed

## Recognition

Contributors will be recognized in:
- README.md contributors section
- Release notes for significant contributions
- GitHub contributor statistics

Thank you for contributing to PHP File Manager! 🎉
