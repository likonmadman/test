FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    librabbitmq-dev \
    libssl-dev \
    libicu-dev \
    libzip-dev \
    zlib1g-dev

# Install the zip PHP extension
RUN docker-php-ext-install zip

# Install the intl PHP extension
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# Install the sockets PHP extension
RUN docker-php-ext-install sockets

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mbstring exif pcntl bcmath gd soap

# Install the amqp PHP extension
RUN pecl install amqp && docker-php-ext-enable amqp

# Install the mongodb PHP extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install ImageMagick
RUN apt-get update \
    # Install libmagickwand-dev imagick package
    && apt-get install -y libmagickwand-dev \
    # use pecl to install imagick
    && pecl install imagick \
    # Enable you installed extension
    && docker-php-ext-enable imagick

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install GD
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Set working directory
WORKDIR /application