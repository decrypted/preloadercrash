#FROM php:7.4-cli
FROM phpdaily/php:7.4.2-dev-cli-stretch

RUN apt-get update && apt-get install -y wget curl unzip gdb

RUN docker-php-ext-install -j$(nproc) opcache

#php --ri "Zend Opcache"
RUN echo "opcache.enable_cli=1"  >> /usr/local/etc/php/conf.d/zzz-preload.ini
RUN echo "opcache.preload=/home/preload.php" >> /usr/local/etc/php/conf.d/zzz-preload.ini
RUN echo "opcache.preload_user=www-data" >> /usr/local/etc/php/conf.d/zzz-preload.ini


WORKDIR /home
