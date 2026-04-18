# Start with PHP 8.2
FROM php:8.2-fpm

# 1. Install System dependencies + Python + Nginx (All in one go)
RUN apt-get update && apt-get install -y \
    nginx \
    python3 \
    python3-pip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# 2. Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring

# 3. Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set the workspace
WORKDIR /var/www

# 5. INSTALL PYTHON LIBRARIES FIRST (For faster builds)
# We copy ONLY the requirements file first so Docker caches this layer
COPY requirements.txt /var/www/requirements.txt
RUN pip3 install --no-cache-dir -r /var/www/requirements.txt --break-system-packages

# 6. Copy the rest of the application
COPY . /var/www

# 7. Configure Nginx
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

# 8. Install Laravel libraries
RUN composer install --no-dev --optimize-autoloader

# 9. Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 10. Start Command
CMD php artisan migrate --force && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan config:cache && \
    service nginx start && \
    php-fpm