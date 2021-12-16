FROM library/php:8.1.0-fpm AS base
#
WORKDIR /srv/
#
# Expose the PHP FPM port.
EXPOSE 9000
#
# Get php-extension-installer in order to simplify the installation process of PHP extensions.
# See https://github.com/mlocati/docker-php-extension-installer.
COPY --from=mlocati/php-extension-installer:1.4.6 /usr/bin/install-php-extensions /usr/local/bin/
#
# Install PHP extensions required by Laravel that are not pre-installed.
# See https://laravel.com/docs/8.x/deployment#server-requirements.
# See https://github.com/mlocati/docker-php-extension-installer#supported-php-extensions.
RUN install-php-extensions bcmath
#
# Install aditional PHP extensions required by the project.
RUN install-php-extensions pdo_mysql
#
# Get Composer in order to install the Composer dependencies.
COPY --from=library/composer:2.1.14 /usr/bin/composer /usr/bin/composer
#
# Install packages required by composer.
RUN apt-get update && apt-get install -y zip
#
#-----------------------------------------------------------------------------------------------------------------------
#
FROM base as development
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
FROM base AS production
#
# Use the default production configuration.
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
#
# Add the project files to the image.
COPY ./ /srv/
#
# Install the Composer dependencies.
RUN composer install --optimize-autoloader --no-dev
#
# Set some environment variables for production usage of the application.
ENV APP_ENV="production"
ENV APP_DEBUG="false"
ENV LOG_LEVEL="error"
#
# Generate the cache for the application.
RUN php artisan config:cache
RUN php artisan route:cache
