#!/bin/bash

# cPanel Folder Structure Setup Script for TimCare ITSM Dashboard
# Usage: bash setup-cpanel-structure.sh
# Run this from the uploaded project directory

echo "🔧 Setting up cPanel folder structure for TimCare ITSM Dashboard..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -d "public" ] || [ ! -f "artisan" ]; then
    echo -e "${RED}Error: Please run this script from the Laravel project root directory${NC}"
    echo -e "${YELLOW}This script should be run immediately after uploading your Laravel project${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 1: Creating application directory structure...${NC}"

# Create timcare directory if it doesn't exist
if [ ! -d "~/timcare" ]; then
    mkdir -p ~/timcare
    echo -e "${GREEN}✓ Created ~/timcare directory${NC}"
else
    echo -e "${GREEN}✓ ~/timcare directory already exists${NC}"
fi

echo -e "${YELLOW}Step 2: Moving application files to timcare directory...${NC}"

# Move all application files (except public) to timcare
mv app ~/timcare/ 2>/dev/null || echo "app directory not found or already moved"
mv config ~/timcare/ 2>/dev/null || echo "config directory not found or already moved"
mv database ~/timcare/ 2>/dev/null || echo "database directory not found or already moved"
mv resources ~/timcare/ 2>/dev/null || echo "resources directory not found or already moved"
mv routes ~/timcare/ 2>/dev/null || echo "routes directory not found or already moved"
mv storage ~/timcare/ 2>/dev/null || echo "storage directory not found or already moved"
mv tests ~/timcare/ 2>/dev/null || echo "tests directory not found or already moved"
mv vendor ~/timcare/ 2>/dev/null || echo "vendor directory not found or already moved"

# Move files
mv artisan ~/timcare/ 2>/dev/null || echo "artisan file not found or already moved"
mv composer.json ~/timcare/ 2>/dev/null || echo "composer.json not found or already moved"
mv composer.lock ~/timcare/ 2>/dev/null || echo "composer.lock not found or already moved"
mv .env* ~/timcare/ 2>/dev/null || echo ".env files not found or already moved"
mv *.md ~/timcare/ 2>/dev/null || echo "markdown files not found or already moved"
mv *.php ~/timcare/ 2>/dev/null || echo "php files not found or already moved"
mv *.sh ~/timcare/ 2>/dev/null || echo "shell scripts not found or already moved"

echo -e "${GREEN}✓ Application files moved to ~/timcare${NC}"

echo -e "${YELLOW}Step 3: Moving public files to public_html...${NC}"

# Move public directory contents to public_html
if [ -d "public" ]; then
    mv public/* ~/public_html/TimCare 2>/dev/null || echo "No files to move from public/"
    mv public/.* ~/public_html/TimCare 2>/dev/null || echo "No hidden files to move from public/"

    # Remove empty public directory
    rmdir public 2>/dev/null || echo "Could not remove public directory (not empty?)"
    echo -e "${GREEN}✓ Public files moved to ~/public_html${NC}"
else
    echo -e "${YELLOW}⚠ Public directory not found${NC}"
fi

echo -e "${YELLOW}Step 4: Updating index.php paths...${NC}"

# Update index.php to point to timcare directory
if [ -f "~/public_html/index.php" ]; then
    sed -i 's|../vendor|../timcare/vendor|g' ~/public_html/TimCare/index.php
    sed -i 's|../bootstrap|../timcare/bootstrap|g' ~/public_html/TimCare/index.php
    echo -e "${GREEN}✓ Updated index.php paths${NC}"
else
    echo -e "${RED}✗ index.php not found in public_html${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 5: Setting up .htaccess security...${NC}"

# Ensure .htaccess exists and has proper security
if [ -f "~/public_html/.htaccess" ]; then
    # Add security rules if not already present
    if ! grep -q "Protect sensitive files" ~/public_html/.htaccess; then
        cat >> ~/public_html/.htaccess << 'EOF'

# Protect sensitive files
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.json">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.lock">
    Order allow,deny
    Deny from all
</Files>

<Files "artisan">
    Order allow,deny
    Deny from all
</Files>

# Protect directories
<Directory "app">
    Order allow,deny
    Deny from all
</Directory>

<Directory "config">
    Order allow,deny
    Deny from all
</Directory>

<Directory "database">
    Order allow,deny
    Deny from all
</Directory>

<Directory "resources">
    Order allow,deny
    Deny from all
</Directory>

<Directory "routes">
    Order allow,deny
    Deny from all
</Directory>

<Directory "storage">
    Order allow,deny
    Deny from all
</Directory>

<Directory "tests">
    Order allow,deny
    Deny from all
</Directory>

<Directory "vendor">
    Order allow,deny
    Deny from all
</Directory>
EOF
        echo -e "${GREEN}✓ Added security rules to .htaccess${NC}"
    else
        echo -e "${GREEN}✓ Security rules already exist in .htaccess${NC}"
    fi
else
    echo -e "${RED}✗ .htaccess not found in public_html${NC}"
fi

echo ""
echo -e "${GREEN}🎉 cPanel folder structure setup completed!${NC}"
echo ""
echo -e "${YELLOW}Current structure:${NC}"
echo "  ~/timcare/     → Application files (secure)"
echo "  ~/public_html/ → Public web files only"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Copy setup scripts to ~/timcare/:"
echo "   cp ~/timcare/deploy.sh ~/timcare/"
echo "   cp ~/timcare/backup.sh ~/timcare/"
echo "   cp ~/timcare/test-deployment.sh ~/timcare/"
echo ""
echo "2. Run deployment script:"
echo "   cd ~/timcare && bash deploy.sh"
echo ""
echo "3. Test your application"
echo ""
echo -e "${YELLOW}Important:${NC}"
echo "- Never put sensitive files in public_html/"
echo "- Keep ~/timcare/ private and secure"
echo "- .env files should only exist in ~/timcare/"