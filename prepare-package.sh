#!/bin/bash

# PHP File Manager - Git Initialization and Publishing Preparation Script
# This script prepares the package for Git repository creation and Packagist publishing

set -e  # Exit on any error

echo "🚀 Preparing nishthatechnosoft/php-file-manager for publication..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Git is installed
if ! command -v git &> /dev/null; then
    print_error "Git is not installed. Please install Git first."
    exit 1
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed. Please install Composer first."
    exit 1
fi

print_status "Validating package structure..."

# Check required files
required_files=(
    "composer.json"
    "README.md"
    "LICENSE"
    "src/FileManager.php"
    "public/index.php"
)

for file in "${required_files[@]}"; do
    if [[ ! -f "$file" ]]; then
        print_error "Required file missing: $file"
        exit 1
    fi
done

print_success "All required files present"

# Validate composer.json
print_status "Validating composer.json..."
if composer validate --quiet; then
    print_success "composer.json is valid"
else
    print_error "composer.json validation failed"
    exit 1
fi

# Install dependencies
print_status "Installing dependencies..."
composer install --no-dev --optimize-autoloader --quiet

print_success "Dependencies installed"

# Run code quality checks
print_status "Running code quality checks..."

# Check PHP syntax
find src/ -name "*.php" -exec php -l {} \; > /dev/null
if [[ $? -eq 0 ]]; then
    print_success "PHP syntax check passed"
else
    print_error "PHP syntax errors found"
    exit 1
fi

# Initialize Git repository if not already initialized
if [[ ! -d ".git" ]]; then
    print_status "Initializing Git repository..."
    git init
    print_success "Git repository initialized"
else
    print_status "Git repository already exists"
fi

# Create .gitattributes file for better package distribution
if [[ ! -f ".gitattributes" ]]; then
    print_status "Creating .gitattributes..."
    cat > .gitattributes << 'EOF'
# Auto detect text files and perform LF normalization
* text=auto

# Export ignore
/.gitattributes export-ignore
/.gitignore export-ignore
/.github export-ignore
/tests export-ignore
/phpunit.xml export-ignore
/CONTRIBUTING.md export-ignore
/PUBLISHING.md export-ignore
/prepare-package.sh export-ignore

# Ensure shell scripts have LF line endings
*.sh text eol=lf

# Ensure batch files have CRLF line endings
*.bat text eol=crlf

# Binary files
*.png binary
*.jpg binary
*.jpeg binary
*.gif binary
*.ico binary
*.zip binary
*.tar.gz binary
EOF
    print_success ".gitattributes created"
fi

# Stage all files
print_status "Staging files for commit..."
git add .

# Check if there are changes to commit
if git diff --staged --quiet; then
    print_warning "No changes to commit"
else
    # Check if user name and email are configured
    if [[ -z $(git config user.name) ]] || [[ -z $(git config user.email) ]]; then
        print_warning "Git user configuration missing. Please configure:"
        echo "  git config --global user.name 'Your Name'"
        echo "  git config --global user.email 'your.email@example.com'"
        echo ""
        print_status "Setting default configuration for this repository..."
        git config user.name "Dhiraj Dhiman"
        git config user.email "dhiraj@nishthatechnosoft.com"
    fi

    # Commit initial files
    print_status "Creating initial commit..."
    git commit -m "feat: initial commit - PHP File Manager v1.0.0

- Complete file management functionality
- PSR-12 compliant codebase
- Composer package structure
- Comprehensive documentation
- Security features and validation
- Responsive web interface
- Bulk operations support
- Archive management capabilities"

    print_success "Initial commit created"
fi

# Create and checkout main branch if on master
current_branch=$(git branch --show-current)
if [[ "$current_branch" == "master" ]]; then
    print_status "Renaming master branch to main..."
    git branch -m main
    print_success "Branch renamed to main"
fi

# Display next steps
echo ""
print_success "Package preparation complete!"
echo ""
echo "📋 Next steps:"
echo ""
echo "1. Create GitHub repository:"
echo "   • Go to https://github.com/new"
echo "   • Repository name: php-file-manager"
echo "   • Description: A modern, feature-rich web-based file manager"
echo "   • Make it public"
echo "   • Don't initialize with README (already exists)"
echo ""
echo "2. Connect local repository to GitHub:"
echo "   git remote add origin https://github.com/dhiraj-nishthatechnosoft/php-file-manager.git"
echo "   git push -u origin main"
echo ""
echo "3. Create first release:"
echo "   git tag -a v1.0.0 -m 'Initial stable release'"
echo "   git push origin v1.0.0"
echo ""
echo "4. Publish to Packagist:"
echo "   • Go to https://packagist.org/"
echo "   • Click 'Submit Package'"
echo "   • Enter: https://github.com/dhiraj-nishthatechnosoft/php-file-manager"
echo "   • Click 'Check' then 'Submit'"
echo ""
echo "5. Set up auto-update webhook:"
echo "   • Go to package settings on Packagist"
echo "   • Copy webhook URL"
echo "   • Add to GitHub repository webhooks"
echo ""
echo "📖 For detailed instructions, see PUBLISHING.md"
echo ""
print_success "Ready for publication! 🎉"
