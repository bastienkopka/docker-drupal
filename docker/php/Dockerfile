FROM php:8.2.8-fpm-alpine3.18 as php

ENV TIMEZONE 'Europe/Paris'
ENV DRUSH_VERSION latest

ARG uid
ARG gid

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
  && pecl install apcu redis uploadprogress xdebug-3.2.2 \
  && docker-php-ext-enable apcu redis uploadprogress xdebug \
  && pecl clear-cache \
  && apk del .build-dependencies

# Add the user to the group.
RUN addgroup -g $gid -S app && adduser -u $uid -S app -G app

# Composer.
COPY --from=composer:2.4.4 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
