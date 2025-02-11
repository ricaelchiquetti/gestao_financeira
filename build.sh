#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x build.sh`
# Build assets using NPM
npm run build
# Clear cache
php artisan optimize:clear
# Cache the various components of the Laravel application
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

php artisan filament:optimize

php artisan vendor:publish --tag=filament-config
php artisan vendor:publish --tag=filament-panels-translations
php artisan vendor:publish --tag=filament-actions-translations
php artisan vendor:publish --tag=filament-forms-translations
php artisan vendor:publish --tag=filament-infolists-translations
php artisan vendor:publish --tag=filament-notifications-translations
php artisan vendor:publish --tag=filament-tables-translations
php artisan vendor:publish --tag=filament-translations