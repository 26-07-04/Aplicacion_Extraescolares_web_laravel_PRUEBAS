#!/bin/bash

# Instalar dependencias de Composer
composer install --no-dev --optimize-autoloader --no-interaction

# Copiar archivo .env si no existe
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generar APP_KEY si no está configurado
php artisan key:generate --force

# Limpiar y cachear configuración
php artisan config:clear
php artisan config:cache

# Limpiar y cachear rutas
php artisan route:clear
php artisan route:cache

# Limpiar y cachear vistas
php artisan view:clear
php artisan view:cache

# Optimizar composer
composer dump-autoload --optimize

# Compilar assets de Vite
npm install
npm run build

# Crear enlace simbólico de storage
php artisan storage:link

# Migraciones
php artisan migrate --force

# Optimizar aplicación
php artisan optimize --force

echo "Build completado exitosamente"
