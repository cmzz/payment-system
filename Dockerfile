############################
#
# build for laravel web app
#
############################
FROM litsoftware/php:7.4-fpm AS fpm

WORKDIR /var/www

COPY . .

RUN chmod -R 777 storage bootstrap

RUN composer -V \
    && composer install --no-dev --no-progress -o


############################
#
# build for laravel cli
#
############################
FROM litsoftware/php:7.4-cli AS cli

WORKDIR /var/www

COPY --from=fpm /var/www/.  /var/www
COPY ./docker/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

CMD ["/usr/local/bin/start"]


############################
#
# build for nginx
#
############################
FROM nginx:latest AS web

WORKDIR /var/www

COPY --from=fpm /var/www/.  /var/www
