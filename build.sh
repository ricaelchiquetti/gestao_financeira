#!/bin/bash
# Garantir permissões de execução: `chmod +x build.sh` 
# Publicando os recursos do Filament
php artisan vendor:publish --tag=filament-config --force
php artisan vendor:publish --tag=filament-panels-translations --force
php artisan vendor:publish --tag=filament-actions-translations --force
php artisan vendor:publish --tag=filament-forms-translations --force
php artisan vendor:publish --tag=filament-infolists-translations --force
php artisan vendor:publish --tag=filament-notifications-translations --force
php artisan vendor:publish --tag=filament-tables-translations --force
php artisan vendor:publish --tag=filament-translations --force

# Build assets usando NPM
npm install
npm run build

# Limpar cache do Laravel
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Cache dos componentes do Laravel
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Otimização do Filament
php artisan filament:optimize
php artisan optimize
