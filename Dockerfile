# Start with PHP 8.2 (for Laravel)
FROM php:8.2-fpm

# Install System dependencies + Python + Nginx
RUN apt-get update && apt-get install -y \
    nginx python3 python3-pip libpng-dev libonig-dev libxml2-dev zip unzip git curl

# Install PHP extensions for TiDB (MySQL compatible)
RUN docker-php-ext-install pdo_mysql mbstring

# Get Composer (Laravel's manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Overwrite the default Nginx configuration
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

WORKDIR /var/www
# Copy the certificates folder into the container
COPY ./certs /var/www/certs
COPY . /var/www

# Install Laravel libraries
RUN composer install --no-dev --optimize-autoloader
# RUN chmod -R 755 /var/www/assets/scripts

# Install your Python libraries (the ones you listed)
# We use --break-system-packages because this is a standalone container
RUN pip3 install --no-cache-dir -r requirements.txt --break-system-packages
RUN pip install google-generativeai mysql-connector-python pandas

# Set permissions so Laravel can write logs
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Start the 'Receptionist' (Nginx) and the 'Manager' (PHP)
CMD php artisan migrate --force && service nginx start && php-fpm