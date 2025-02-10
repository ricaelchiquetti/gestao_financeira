#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x build.sh`
./vendor/bin/sail build
./vendor/bin/sail up

# Build assets using NPM
./vendor/bin/sail npm install
./vendor/bin/sail npm run build

# Clear cache
./vendor/bin/sail artisan optimize:clear

# Cache the various components of the Laravel application
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan event:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache