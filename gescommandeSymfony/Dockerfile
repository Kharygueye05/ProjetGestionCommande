FROM php:8.4-cli

# Dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    curl \
    && docker-php-ext-install \
        intl \
        zip \
        pdo \
        pdo_pgsql \
        pgsql \
    && docker-php-ext-enable pdo_pgsql \
    && apt-get clean

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /app

# Copier le projet Symfony
COPY . .

# Installer dépendances DEV
RUN composer install --no-interaction

# Vérification du driver PostgreSQL
RUN php -m | grep -i pgsql && echo "✅ PostgreSQL driver OK"

# Exposer le port utilisé par PHP
EXPOSE 8000

# Lancer Symfony avec le serveur PHP intégré
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
