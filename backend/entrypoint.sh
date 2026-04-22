#!/bin/sh
set -e

echo "==> Gerando .env a partir das variáveis de ambiente do container..."
cat > /var/www/.env <<EOF
APP_NAME=${APP_NAME:-TriAL}
APP_ENV=${APP_ENV:-local}
APP_KEY=
APP_DEBUG=${APP_DEBUG:-true}
APP_TIMEZONE=${APP_TIMEZONE:-America/Sao_Paulo}
APP_URL=${APP_URL:-http://localhost:8000}
APP_LOCALE=${APP_LOCALE:-pt_BR}
APP_FALLBACK_LOCALE=${APP_FALLBACK_LOCALE:-pt_BR}

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_STACK=${LOG_STACK:-single}
LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

CACHE_STORE=${CACHE_STORE:-file}
SESSION_DRIVER=${SESSION_DRIVER:-file}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}

MAIL_MAILER=${MAIL_MAILER:-log}
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-noreply@trial.local}
MAIL_FROM_NAME="${MAIL_FROM_NAME:-TriAL Platform}"

JWT_SECRET=${JWT_SECRET:-}
JWT_ALGO=${JWT_ALGO:-HS256}
JWT_TTL=${JWT_TTL:-60}
JWT_REFRESH_TTL=${JWT_REFRESH_TTL:-20160}

ROOT_EMAIL=${ROOT_EMAIL:-root@trial.com}
ROOT_SENHA=${ROOT_SENHA:-root123}
ROOT_NOME=${ROOT_NOME:-Administrador}
ROOT_TELEFONE=${ROOT_TELEFONE:-99999999999}
ROOT_NASCIMENTO=${ROOT_NASCIMENTO:-1990-01-01}
EOF

echo "==> Corrigindo permissões de storage..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "==> Aguardando o banco de dados..."
sleep 2

echo "==> Gerando APP_KEY..."
php artisan key:generate --no-interaction --force

echo "==> Publicando config do JWT..."
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider" --no-interaction 2>/dev/null || true

echo "==> Gerando JWT secret..."
php artisan jwt:secret --force --no-interaction 2>/dev/null || true

echo "==> Executando migrations..."
php artisan migrate --force --no-interaction

echo "==> Garantindo usuário root..."
php artisan db:seed --class=RootUsuarioSeeder --force --no-interaction

echo "==> Iniciando serviço..."
exec "$@"
