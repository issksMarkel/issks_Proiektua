FROM php:8.1-apache
WORKDIR /var/www/html
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
RUN echo "<Directory /var/www/html>\nAllowOverride All\n</Directory>" >> /etc/apache2/apache2.conf
