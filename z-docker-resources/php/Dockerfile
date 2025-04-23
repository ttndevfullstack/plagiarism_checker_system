
FROM php:8.1-fpm-alpine

# php:8.1-fpm-alpine already included: json, mysqlnd, curl, xml, mbstring

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install sockets

RUN apk add php-xml
RUN apk add php-xmlwriter
RUN apk add php-dom
RUN apk add php-tokenizer
RUN apk add php-intl
RUN apk add php-gd

RUN apk add icu-dev
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-enable sockets

RUN apk add bash
RUN apk add composer

COPY ./upload.ini /usr/local/etc/php/conf.d/uploads.ini

# Crontab
COPY ./crontab /var/spool/cron/crontabs/root
COPY cron-entrypoint.bash /usr/sbin
RUN chmod a+x /usr/sbin/cron-entrypoint.bash
#ENTRYPOINT /usr/sbin/cron-entrypoint.bash
