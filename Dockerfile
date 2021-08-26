FROM php:7.4-fpm

ENV ACCEPT_EULA=Y

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev libxpm-dev \
    libfreetype6-dev \
    zip \
    unzip \
    gcc \
    gnupg \
    autoconf \
    supervisor \
    nginx \
    nodejs \
    npm \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    procps

# Microsoft SQL Server Prerequisites
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/10/prod.list \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && apt-get -y install \
        unixodbc-dev \
        msodbcsql17

# Install redis
RUN pecl install -o -f redis pdo_sqlsrv && rm -rf /tmp/pear
RUN docker-php-ext-enable redis pdo_sqlsrv

# Install laravel-echo-server and svgo globally
RUN npm install --global --unsafe-perm laravel-echo-server svgo

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd opcache

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 

# Add configs
COPY .docker/openssl.cnf /etc/ssl/openssl.cnf
COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY .docker/php-memory-limit.ini /usr/local/etc/php/conf.d/php-memory-limit.ini

# Copy app abd set permissions
COPY . /app
RUN chown -R www-data.www-data ./

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/app/.docker/supervisord.conf"]

# Set working directory
WORKDIR /app

# Open port
EXPOSE 8080