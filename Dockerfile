FROM php:8.2-apache

# Install PHP extensions needed for MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copy project files if needed (optional, because you already mount with volumes)
