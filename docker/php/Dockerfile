FROM php:8.2.8-fpm-alpine3.18 as php

ENV TIMEZONE 'Europe/Paris'
ENV DRUSH_VERSION latest

ARG uid
ARG gid

# Composer.
COPY --from=composer:2.4.4 /usr/bin/composer /usr/bin/composer

RUN set -eux ; \
    \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        icu-dev \
        freetype-dev \
        gettext-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libzip-dev \
        libwebp-dev \
        libxpm-dev ; \
    apk add --update --no-cache \
        linux-headers \
        sudo \
        bash \
        curl \
        git \
        patch \
        icu \
        icu-data-full \
        freetype \
        gettext \
        mariadb-client \
        libjpeg-turbo \
        libpng \
        libzip \
        libwebp \
        libxpm ; \
    docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
        --with-xpm  ; \
    docker-php-ext-install -j "$(nproc)" \
        bcmath \
        gd \
        opcache \
        pdo_mysql \
        gettext \
        intl \
        zip ; \
    apk del .build-deps

RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
  && pecl install apcu redis uploadprogress \
  && docker-php-ext-enable apcu redis uploadprogress \
  && pecl clear-cache \
  && apk del .build-dependencies

# Add the user to the group.
RUN addgroup -g $gid -S app && adduser -u $uid -S app -G app

WORKDIR /var/www


FROM php as dev

RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
  && pecl install xdebug-3.2.2 \
  && docker-php-ext-enable xdebug \
  && pecl clear-cache \
  && apk del .build-dependencies

COPY conf/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini