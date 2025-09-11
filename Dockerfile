# ベースイメージ
FROM php:8-apache

# 作業ディレクトリ
WORKDIR /var/www/html

# 必要なライブラリと拡張のインストール
RUN apt-get update \
    && apt-get install -y libonig-dev libzip-dev unzip libpq-dev git \
    && docker-php-ext-install mbstring zip bcmath pdo_pgsql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && mkdir -p /var/log \
    && touch /var/log/xdebug.log \
    && chown www-data:www-data /var/log/xdebug.log

# Apache mod_rewrite 有効化
RUN a2enmod rewrite

# Composer インストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# 設定ファイルのコピー
COPY ./docker/app/php.ini /usr/local/etc/php/php.ini
COPY ./docker/app/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./docker/app/000-default.conf /etc/apache2/sites-enabled/000-default.conf

# アプリケーションファイル・Composer関連をまとめてコピー
COPY ./src ./vendor ./composer.json ./composer.lock /var/www/html/
