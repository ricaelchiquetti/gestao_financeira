#!/bin/bash

# Garantir permissões de execução: `chmod +x build.sh` 

# Verificar se o PHP está instalado
if ! command -v php &> /dev/null
then
    echo "PHP não encontrado, por favor instale o PHP para continuar."
    exit 1
fi

# Verificar se o NPM está instalado
if ! command -v npm &> /dev/null
then
    echo "NPM não encontrado, por favor instale o NPM para continuar."
    exit 1
fi

# Publicando os recursos do Filament
echo "Publicando recursos do Filament..."
php artisan vendor:publish --tag=filament-config --force
php artisan vendor:publish --tag=filament-panels-translations --force
php artisan vendor:publish --tag=filament-actions-translations --force
php artisan vendor:publish --tag=filament-forms-translations --force
php artisan vendor:publish --tag=filament-infolists-translations --force
php artisan vendor:publish --tag=filament-notifications-translations --force
php artisan vendor:publish --tag=filament-tables-translations --force
php artisan vendor:publish --tag=filament-translations --force

# Build assets usando NPM
echo "Construindo os assets com NPM..."
npm install
npm run build

# Limpar cache do Laravel
echo "Limpando cache..."
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Cache dos componentes do Laravel
echo "Recriando cache de configuração, rotas e eventos..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache


# Otimização do Filament
echo "Otimização do Filament e Laravel..."
php artisan filament:optimize
php artisan optimize

echo "Construção concluída com sucesso!"
