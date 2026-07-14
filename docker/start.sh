#!/usr/bin/env sh

set -eu

cd /var/www/html

mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        cat > .env <<EOF
APP_NAME="ATSA Tucuman"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=${APP_URL:-http://localhost}
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_AR
APP_TIMEZONE=America/Argentina/Tucuman
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_LEVEL=warning
DB_CONNECTION=${DB_CONNECTION:-sqlite}
SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=${FILESYSTEM_DISK:-public}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}
CACHE_STORE=${CACHE_STORE:-file}
MAIL_MAILER=${MAIL_MAILER:-log}
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="ATSA Tucuman"
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
VITE_APP_NAME="ATSA Tucuman"
EOF
    fi
fi

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ] && [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

if [ -z "${APP_KEY:-}" ]; then
    php artisan key:generate --force
fi

php artisan storage:link || true
php artisan optimize:clear || true
php artisan package:discover --ansi
php artisan migrate --force

if [ "${RUN_DEMO_SEEDERS:-false}" = "true" ]; then
    echo "Running ATSA demo seeders..."
    php artisan db:seed --class=SecretariasBaseSeeder --force || true
    php artisan db:seed --class=BeneficiosSeeder --force || true
    php artisan db:seed --class=DemoAccessSeeder --force || true
    php artisan db:seed --class=CentContenidoRealSeeder --force || true
    php artisan db:seed --class=CentConfiguracionSeeder --force || true
    php artisan db:seed --class=CentPremiumDemoSeeder --force || true
    php artisan db:seed --class=CentDemoSeeder --force || true
else
    echo "Skipping ATSA demo seeders. RUN_DEMO_SEEDERS is not true."
fi

php artisan config:cache || true
php artisan view:cache || true

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
