# Dockerfile
FROM php:8.4-cli

# Installer les bases
RUN apt-get update && apt-get install -y unzip git curl

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier tout le projet
WORKDIR /app
COPY . .

# Installer les dépendances (sans dev)
RUN composer install --no-dev --optimize-autoloader

# Nettoyer le cache
RUN rm -rf var/cache/*

# Reconstruire le cache de production
RUN APP_ENV=prod php bin/console cache:clear

# Démarrer le serveur
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]