# Use the official PHP 7.4 image with Apache
FROM php:7.4-apache

# Set working directory
WORKDIR /var/www/html

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy source files to the working directory
COPY . /var/www/html

# Update the default apache site with the config we created.
ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

# Expose port 80
EXPOSE 80
