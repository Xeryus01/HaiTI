#!/bin/bash

# Database Backup Script for cPanel - TimCare ITSM Dashboard
# Usage: bash backup.sh

BACKUP_DIR="backups"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_NAME="timcare_backup_$DATE.sql"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}Starting database backup...${NC}"

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Check if .env exists
if [ ! -f ".env" ]; then
    echo -e "${RED}Error: .env file not found${NC}"
    exit 1
fi

# Extract database credentials from .env (in timcare directory)
if [ -f "~/timcare/.env" ]; then
    DB_HOST=$(grep "DB_HOST=" ~/timcare/.env | cut -d '=' -f2 | tr -d '"')
    DB_PORT=$(grep "DB_PORT=" ~/timcare/.env | cut -d '=' -f2 | tr -d '"')
    DB_DATABASE=$(grep "DB_DATABASE=" ~/timcare/.env | cut -d '=' -f2 | tr -d '"')
    DB_USERNAME=$(grep "DB_USERNAME=" ~/timcare/.env | cut -d '=' -f2 | tr -d '"')
    DB_PASSWORD=$(grep "DB_PASSWORD=" ~/timcare/.env | cut -d '=' -f2 | tr -d '"')
else
    echo -e "${RED}Error: .env file not found in ~/timcare/.env${NC}"
    exit 1
fi

# Check if credentials are set
if [ -z "$DB_DATABASE" ] || [ -z "$DB_USERNAME" ]; then
    echo -e "${RED}Error: Database credentials not found in .env${NC}"
    exit 1
fi

echo -e "${YELLOW}Creating backup: $BACKUP_NAME${NC}"

# Create backup using mysqldump
mysqldump --host=$DB_HOST --port=$DB_PORT --user=$DB_USERNAME --password=$DB_PASSWORD $DB_DATABASE > $BACKUP_DIR/$BACKUP_NAME

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database backup created successfully: $BACKUP_DIR/$BACKUP_NAME${NC}"

    # Compress the backup
    gzip $BACKUP_DIR/$BACKUP_NAME
    echo -e "${GREEN}✓ Backup compressed: $BACKUP_DIR/${BACKUP_NAME}.gz${NC}"

    # Remove backups older than 30 days
    find $BACKUP_DIR -name "*.gz" -type f -mtime +30 -delete
    echo -e "${GREEN}✓ Old backups cleaned up${NC}"

    # Show backup size
    SIZE=$(du -h $BACKUP_DIR/${BACKUP_NAME}.gz | cut -f1)
    echo -e "${GREEN}✓ Backup size: $SIZE${NC}"

else
    echo -e "${RED}✗ Database backup failed${NC}"
    exit 1
fi

echo -e "${GREEN}Backup completed successfully!${NC}"
echo -e "${YELLOW}Backup location: $BACKUP_DIR/${BACKUP_NAME}.gz${NC}"