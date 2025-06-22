FROM php:8.1-fpm

# Install PHP extensions via helper script
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    apt-get update && apt-get install -y git unzip && \
    install-php-extensions @composer-2.6.6 xdebug-3.2.2 zip pdo_mysql intl

# Set working directory
WORKDIR /app

# Add runtime user matching the host UID/GID
ARG DOCKER_USER_ID=1000
ARG DOCKER_GROUP_ID=1000

RUN groupadd -g ${DOCKER_GROUP_ID} appgroup && \
    useradd -m -u ${DOCKER_USER_ID} -g appgroup appuser || true


# Configure Xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
