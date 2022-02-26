FROM library/php:8.1.3-fpm AS base
#
WORKDIR /srv/
#
# Get php-extension-installer in order to simplify the installation process of PHP extensions.
# See https://github.com/mlocati/docker-php-extension-installer.
COPY --from=mlocati/php-extension-installer:1.4.16 /usr/bin/install-php-extensions /usr/local/bin/
#
# Install PHP extensions required by Laravel that are not pre-installed.
# See https://laravel.com/docs/8.x/deployment#server-requirements.
# See https://github.com/mlocati/docker-php-extension-installer#supported-php-extensions.
RUN install-php-extensions bcmath
#
# Install aditional PHP extensions required by the project.
RUN install-php-extensions pdo_mysql
#
#-----------------------------------------------------------------------------------------------------------------------
#
FROM base as composer
#
# Get Composer in order to install the Composer dependencies.
COPY --from=library/composer:2.2.6 /usr/bin/composer /usr/bin/composer
#
# Install packages required by composer.
RUN apt-get update && apt-get install -y zip
#
#-----------------------------------------------------------------------------------------------------------------------
#
FROM composer as development
#
# Install XDebug's PHP extensions in order to debug the PHP code.
# You can configure it using environment variables.
# See https://xdebug.org/docs/all_settings.
RUN install-php-extensions xdebug
#
# Use the default development configuration.
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
#
#-----------------------------------------------------------------------------------------------------------------------
#
FROM composer AS composer_dependencies
#
# Add the project files to the image.
COPY ./ /srv/
#
# Install the Composer dependencies.
RUN composer install --optimize-autoloader --no-dev
#
#-----------------------------------------------------------------------------------------------------------------------
#
FROM base AS production
#
# Use the default production configuration.
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
#
# Enable PHP FPM status page.
RUN echo "pm.status_path = /status" >> /usr/local/etc/php-fpm.d/zz-docker.conf
#
# Add a PHP FPM health checker.
RUN curl -o /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck
#
# Setup a health checker.
HEALTHCHECK --interval=5s --timeout=1s CMD php-fpm-healthcheck || exit 1
#
# Add the project files to the image.
COPY ./ /srv/
#
# Add the Composer dependencies to the image.
COPY --from=composer_dependencies /srv/vendor/ /srv/vendor/
#
# Set some environment variables for production usage of the application.
ENV APP_ENV="production"
ENV APP_DEBUG="false"
ENV LOG_LEVEL="error"
#
# Generate the cache for the application.
RUN php artisan route:cache
