FROM php:8.4-apache
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

RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction

# Vérification CRITIQUE
RUN php -m | grep -i pgsql && echo "✅ PostgreSQL OK"

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Permissions Symfony
RUN chown -R www-data:www-data var

EXPOSE 80

CMD ["apache2-foreground"]
