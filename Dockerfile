FROM php:8.3-apache

# Install system dependencies + Node.js 20 via nodesource
RUN apt-get update && apt-get install -y \
    curl \
    git \
    zip \
    unzip \
    pkg-config \
    libgd-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libpq-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (only those NOT already built-in to php:8.3-apache)
# Already built-in: curl, dom, fileinfo, json, pdo, tokenizer, xml
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo_mysql \
        pgsql \
        pdo_pgsql \
        mbstring \
        zip \
        exif \
        pcntl \
        bcmath \
        intl \
        opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Node dependencies (dev deps needed for Vite/Tailwind build) and build frontend
# Use NODE_ENV=development because Railway sets production by default, which skips devDependencies.
# Use corepack to switch to npm 10.9.2 (avoids buggy npm 10.8.2).
# 'corepack use' sets packageManager in package.json so corepack intercepts all npm calls.
RUN corepack enable && corepack use npm@10.9.2 \
    && NODE_ENV=development npm install --no-audit --no-fund \
    && npm run build

# Install PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configure Apache to point to Laravel's public directory
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
        Options -Indexes +FollowSymLinks\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Configure PHP OPcache for production
RUN echo "opcache.enable=1\n\
opcache.memory_consumption=128\n\
opcache.interned_strings_buffer=8\n\
opcache.max_accelerated_files=10000\n\
opcache.revalidate_freq=60\n\
opcache.fast_shutdown=1" > /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 80

CMD bash -c "\
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    apache2-foreground"
