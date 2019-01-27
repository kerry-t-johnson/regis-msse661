FROM    php:7.2-apache AS production

# ============================================================================
# Production image
# ----------------------------------------------------------------------------
#
# Reference(s):
#   https://hub.docker.com/_/php/
#   https://bitpress.io/simple-approach-using-docker-with-php/
COPY    docker_context/ /
RUN     ln -s ${PHP_INI_DIR}/php.ini-production ${PHP_INI_DIR}/php.ini  &&  \
        a2enmod rewrite                                                 &&  \
        docker-php-ext-install mbstring                                 &&  \
        docker-php-ext-install pdo                                      &&  \
        docker-php-ext-install pdo_mysql
COPY    public_html/ /var/www/html/
WORKDIR /var/www/html

# ============================================================================
# Development/debug image
# ----------------------------------------------------------------------------
FROM    production AS dev
RUN     rm -f ${PHP_INI_DIR}/php.ini                                    &&  \
        ln -s ${PHP_INI_DIR}/php.ini-development ${PHP_INI_DIR}/php.ini &&  \
        pecl install xdebug-2.6.0                                       &&  \
        docker-php-ext-enable xdebug

FROM    production
