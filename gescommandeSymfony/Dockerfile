# Utiliser l'image PHP 8.4 CLI officielle
FROM php:8.4-cli

# Installer dépendances système nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip curl \
    && docker-php-ext-install intl zip pdo pdo_mysql

# Installer Composer globalement
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /app

# Copier tous les fichiers du projet
COPY . .

# Installer les dépendances PHP du projet
RUN composer install --optimize-autoloader

# Exposer le port pour Symfony
EXPOSE 8000

# Lancer le serveur intégré Symfony
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]



