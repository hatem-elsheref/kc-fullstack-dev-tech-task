FROM php:8.3-apache

# Install necessary dependencies and MySQL extension
RUN apt-get update && \
    docker-php-ext-install mysqli pdo_mysql && \
    rm -rf /var/lib/apt/lists/*