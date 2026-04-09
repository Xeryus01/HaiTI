#!/bin/bash

# Test Deployment Script for cPanel - TimCare ITSM Dashboard
# Run this after deployment to verify everything works

echo "🧪 Testing TimCare ITSM Dashboard Deployment..."

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Test 1: Check if Laravel is installed
echo -e "${YELLOW}Test 1: Checking Laravel installation...${NC}"
if php artisan --version > /dev/null 2>&1; then
    echo -e "${GREEN}✓ Laravel is installed${NC}"
else
    echo -e "${RED}✗ Laravel not found${NC}"
    exit 1
fi

# Test 2: Check database connection
echo -e "${YELLOW}Test 2: Testing database connection...${NC}"
if php artisan migrate:status > /dev/null 2>&1; then
    echo -e "${GREEN}✓ Database connection successful${NC}"
else
    echo -e "${RED}✗ Database connection failed${NC}"
    exit 1
fi

# Test 3: Check storage link
echo -e "${YELLOW}Test 3: Checking storage symlink...${NC}"
if [ -L "public/storage" ]; then
    echo -e "${GREEN}✓ Storage symlink exists${NC}"
else
    echo -e "${RED}✗ Storage symlink missing${NC}"
    echo -e "${YELLOW}Run: php artisan storage:link${NC}"
fi

# Test 4: Check file permissions
echo -e "${YELLOW}Test 4: Checking file permissions...${NC}"
if [ -w "storage" ] && [ -w "bootstrap/cache" ]; then
    echo -e "${GREEN}✓ Storage and cache directories are writable${NC}"
else
    echo -e "${RED}✗ Storage or cache directories not writable${NC}"
    echo -e "${YELLOW}Run: chmod -R 755 storage bootstrap/cache${NC}"
fi

# Test 5: Check environment configuration
echo -e "${YELLOW}Test 5: Checking environment configuration...${NC}"
if grep -q "APP_ENV=production" .env 2>/dev/null; then
    echo -e "${GREEN}✓ Environment set to production${NC}"
else
    echo -e "${YELLOW}⚠ Environment not set to production${NC}"
fi

if grep -q "APP_DEBUG=false" .env 2>/dev/null; then
    echo -e "${GREEN}✓ Debug mode disabled${NC}"
else
    echo -e "${RED}✗ Debug mode still enabled in production${NC}"
fi

# Test 6: Check cache status
echo -e "${YELLOW}Test 6: Checking cache status...${NC}"
if [ -f "bootstrap/cache/config.php" ]; then
    echo -e "${GREEN}✓ Configuration cache exists${NC}"
else
    echo -e "${YELLOW}⚠ Configuration not cached${NC}"
    echo -e "${YELLOW}Run: php artisan config:cache${NC}"
fi

if [ -f "bootstrap/cache/routes.php" ]; then
    echo -e "${GREEN}✓ Routes cache exists${NC}"
else
    echo -e "${YELLOW}⚠ Routes not cached${NC}"
    echo -e "${YELLOW}Run: php artisan route:cache${NC}"
fi

if [ -f "bootstrap/cache/views.php" ]; then
    echo -e "${GREEN}✓ Views cache exists${NC}"
else
    echo -e "${YELLOW}⚠ Views not cached${NC}"
    echo -e "${YELLOW}Run: php artisan view:cache${NC}"
fi

# Test 7: Test basic routes
echo -e "${YELLOW}Test 7: Testing basic routes...${NC}"
if curl -s -o /dev/null -w "%{http_code}" http://localhost/ | grep -q "200\|302"; then
    echo -e "${GREEN}✓ Web server responding${NC}"
else
    echo -e "${RED}✗ Web server not responding${NC}"
fi

echo ""
echo -e "${GREEN}🎉 Deployment tests completed!${NC}"
echo ""
echo -e "${YELLOW}If all tests passed, your TimCare ITSM Dashboard is ready for production!${NC}"
echo -e "${YELLOW}Don't forget to:${NC}"
echo "1. Set up SSL certificate"
echo "2. Configure cron jobs for queue processing"
echo "3. Set up automated backups"
echo "4. Monitor logs regularly"