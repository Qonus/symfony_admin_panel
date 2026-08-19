# --- vendor ---
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --optimize-autoloader

# --- runtime ---
FROM dunglas/frankenphp:1-php8.3-alpine AS app
RUN install-php-extensions pdo_pgsql
WORKDIR /app
COPY --from=vendor /app/vendor ./vendor
COPY . .
ENV DATABASE_URL="postgresql://null:null@127.0.0.1:5432/null?serverVersion=16"
RUN php bin/console cache:warmup --env=prod
ENV SERVER_NAME=":80"
EXPOSE 80