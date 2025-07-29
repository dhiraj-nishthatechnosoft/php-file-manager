#!/bin/bash

# PHP File Manager - Packagist Publishing Guide
# Run this script after pushing to GitHub to publish on Packagist

set -e

echo "🚀 Publishing nishthatechnosoft/php-file-manager to Packagist..."

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${BLUE}📦 Package Information:${NC}"
echo "  Name: nishthatechnosoft/php-file-manager"
echo "  Repository: https://github.com/dhiraj-nishthatechnosoft/php-file-manager"
echo "  Version: v1.0.0"
echo "  License: MIT"
echo ""

echo -e "${YELLOW}🌟 Next Steps to Publish on Packagist:${NC}"
echo ""
echo "1. 🔐 Create Packagist Account:"
echo "   • Go to: https://packagist.org/"
echo "   • Click 'Sign up' or 'Login'"
echo "   • Connect with your GitHub account"
echo ""

echo "2. 📦 Submit Package:"
echo "   • Go to: https://packagist.org/packages/submit"
echo "   • Enter Repository URL: https://github.com/dhiraj-nishthatechnosoft/php-file-manager"
echo "   • Click 'Check'"
echo "   • Review package information"
echo "   • Click 'Submit'"
echo ""

echo "3. 🔄 Set up Auto-Update Webhook:"
echo "   • After submission, go to your package page"
echo "   • Click 'Settings' or 'Manage'"
echo "   • Copy the webhook URL"
echo "   • Go to GitHub repo settings > Webhooks"
echo "   • Add new webhook with the Packagist URL"
echo "   • Set Content-type: application/json"
echo "   • Select 'Just the push event'"
echo ""

echo "4. ✅ Verify Installation:"
echo "   • Wait 5-10 minutes for indexing"
echo "   • Test: composer require nishthatechnosoft/php-file-manager"
echo ""

echo -e "${GREEN}🎉 After Publishing:${NC}"
echo ""
echo "Users can install your package with:"
echo "  composer require nishthatechnosoft/php-file-manager"
echo ""
echo "The package will automatically:"
echo "  • Create public/index.php entry point"
echo "  • Set up filemanager-config.php"
echo "  • Copy all assets and views"
echo "  • Run post-install setup"
echo ""

echo -e "${BLUE}📊 Package Statistics:${NC}"
wc -l src/**/*.php | tail -1 | awk '{print "  Source Lines: " $1}'
find . -name "*.md" | wc -l | awk '{print "  Documentation Files: " $1}'
find . -name "*.php" | wc -l | awk '{print "  PHP Files: " $1}'
echo "  Dependencies: Development only"
echo "  PHP Version: 8.0+"
echo "  Standards: PSR-12 Compliant"
echo ""

echo -e "${GREEN}✨ Ready for Packagist! ✨${NC}"
