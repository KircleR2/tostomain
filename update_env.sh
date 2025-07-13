#!/bin/bash

# Script to update environment variables on the server
# This should be run on the server where the application is deployed

# Check if we're root or using sudo
if [ "$EUID" -ne 0 ]; then
  echo "Please run as root or with sudo"
  exit 1
fi

# Path to the .env file
ENV_FILE="/var/www/html/.env"

# Check if the file exists
if [ ! -f "$ENV_FILE" ]; then
  echo "Error: .env file not found at $ENV_FILE"
  exit 1
fi

# Backup the original file
cp "$ENV_FILE" "${ENV_FILE}.bak"
echo "Created backup at ${ENV_FILE}.bak"

# Update LOG_CHANNEL to daily
if grep -q "^LOG_CHANNEL=" "$ENV_FILE"; then
  # Replace existing LOG_CHANNEL line
  sed -i 's/^LOG_CHANNEL=.*/LOG_CHANNEL=daily/' "$ENV_FILE"
  echo "Updated LOG_CHANNEL to daily"
else
  # Add LOG_CHANNEL line if it doesn't exist
  echo "LOG_CHANNEL=daily" >> "$ENV_FILE"
  echo "Added LOG_CHANNEL=daily"
fi

# Add ROLLBAR_TOKEN if it doesn't exist
if ! grep -q "^ROLLBAR_TOKEN=" "$ENV_FILE"; then
  echo "ROLLBAR_TOKEN=placeholder_token" >> "$ENV_FILE"
  echo "Added placeholder ROLLBAR_TOKEN"
fi

# Clear application cache
cd /var/www/html
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Environment variables updated and cache cleared"
echo "Please replace the placeholder ROLLBAR_TOKEN with a real token if needed" 