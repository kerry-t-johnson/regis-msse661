FROM    php:7.2-apache AS base
ENV     WEB_ROOT=/var/www/html                  \
        APP_ROOT=/var/www/webapp

# ============================================================================
# Base image
# ----------------------------------------------------------------------------
#
# Reference(s):
#   https://hub.docker.com/_/php/
#   https://bitpress.io/simple-approach-using-docker-with-php/
COPY    docker_context/ /

# General apt based prerequisites
RUN     apt-get -qq update                                                  &&  \
        apt-get -qq install -y --no-install-recommends                          \
            git                                                                 \
            unzip                                                               \
            wget                                                                \
            zip

# Apache
RUN     a2enmod -q rewrite

# PHP modules
RUN     echo "Installing mysqli..."                                         &&  \
        (docker-php-ext-install mysqli > /dev/null)

# Composer
RUN     wget --quiet -O /tmp/composer-setup.php                                 \
            https://getcomposer.org/installer                               &&  \
        php /tmp/composer-setup.php --no-ansi                                   \
                                    --install-dir=/usr/local/bin                \
                                    --filename=composer                         \
                                    --snapshot

COPY    webapp/composer.json            ${APP_ROOT}/
RUN     COMPOSER_ALLOW_SUPERUSER=1 composer --working-dir=${APP_ROOT}        \
            install

WORKDIR ${WEB_ROOT}

# ============================================================================
# Development/debug image
# ----------------------------------------------------------------------------
FROM    base AS dev
RUN     rm -f ${PHP_INI_DIR}/php.ini                                    &&  \
        ln -s ${PHP_INI_DIR}/php.ini-development ${PHP_INI_DIR}/php.ini

RUN     wget --quiet -O /usr/local/bin/phpunit                              \
            https://phar.phpunit.de/phpunit-7.phar                      &&  \
        chmod +x /usr/local/bin/phpunit                                 &&  \
        /usr/local/bin/phpunit --version                                &&  \
        pecl -q install xdebug-2.6.0                                    &&  \
        (docker-php-ext-enable xdebug > /dev/null)

FROM    base AS production

RUN     ln -s ${PHP_INI_DIR}/php.ini-production ${PHP_INI_DIR}/php.ini

COPY    html/                           ${WEB_ROOT}/
COPY    webapp/config/ webapp/src/      ${APP_ROOT}/

