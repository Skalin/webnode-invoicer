FROM php:8.3-fpm-alpine3.21


RUN apk update && apk add \
    libzip-dev \
    libxslt-dev \
    icu-dev \
    oniguruma-dev

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl intl opcache exif mbstring \
    sysvmsg sysvsem sysvshm


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the php config file
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copy the application code
COPY . /app

VOLUME ["/app"]