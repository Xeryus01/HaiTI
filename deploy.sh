#!/bin/bash

# TimCare ITSM Dashboard - cPanel Deployment Script
# Usage: bash deploy.sh

echo "🚀 Starting TimCare ITSM Dashboard Deployment for cPanel..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory (should be ~/timcare)
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: Please run this script from the timcare application directory (~/timcare)${NC}"
    echo -e "${YELLOW}Make sure you've set up the folder structure correctly:${NC}"
    echo "  ~/timcare/     (application files)"
    echo "  ~/public_html/ (public files only)"
    exit 1
fi

echo -e "${YELLOW}Step 1: Installing Composer dependencies...${NC}"
composer install --no-dev --optimize-autoloader

if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Composer install failed${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Composer dependencies installed${NC}"

echo -e "${YELLOW}Step 2: Checking environment file...${NC}"
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}Warning: .env file not found. Please create it from .env.example${NC}"
    echo -e "${YELLOW}Run: cp .env.example .env${NC}"
    echo -e "${YELLOW}Then edit .env with your database and mail settings${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Environment file exists${NC}"

echo -e "${YELLOW}Step 3: Generating application key...${NC}"
php artisan key:generate

if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Could not generate application key${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Application key generated${NC}"

echo -e "${YELLOW}Step 4: Running database migrations...${NC}"
php artisan migrate --force

if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Database migration failed${NC}"
    echo -e "${YELLOW}Please check your database configuration in .env${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Database migrations completed${NC}"

echo -e "${YELLOW}Step 5: Seeding database...${NC}"
php artisan db:seed --force

if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Database seeding failed${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Database seeded${NC}"

echo -e "${YELLOW}Step 6: Creating storage symlink...${NC}"
# Create symlink from public_html to timcare storage
cd ~/public_html
if [ ! -L "storage" ]; then
    ln -s ../timcare/storage/app/public storage
    echo -e "${GREEN}✓ Storage symlink created${NC}"
else
    echo -e "${GREEN}✓ Storage symlink already exists${NC}"
fi

# Back to application directory
cd ~/timcare

echo -e "${YELLOW}Step 7: Setting permissions...${NC}"
chmod -R 755 storage
chmod -R 755 bootstrap/cache

if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Could not set permissions${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Permissions set${NC}"

echo -e "${YELLOW}Step 8: Caching configuration for production...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Could not cache configuration${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Configuration cached${NC}"

echo -e "${YELLOW}Step 9: Clearing all caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo -e "${GREEN}✓ Caches cleared${NC}"

echo ""
echo -e "${GREEN}🎉 Deployment completed successfully!${NC}"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Test your application at your domain"
echo "2. Set up SSL certificate if not already done"
echo "3. Configure cron jobs if using queues:"
echo "   * * * * * cd /home/username/timcare && php artisan queue:work --sleep=3 --tries=3"
echo "4. Set up automated backups for your database"
echo ""
echo -e "${YELLOW}Important:${NC}"
echo "- Make sure APP_DEBUG=false in production"
echo "- Keep your .env file secure and not accessible via web"
echo "- Monitor logs in storage/logs/ for any issues"