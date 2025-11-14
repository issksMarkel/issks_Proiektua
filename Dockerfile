FROM php:8.1-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli && \
    a2enmod rewrite && \
    a2enmod headers

# Configuración de seguridad de PHP en php.ini
RUN echo "session.cookie_httponly = 1" >> /usr/local/etc/php/php.ini && \
    echo "session.cookie_secure = 0" >> /usr/local/etc/php/php.ini && \
    echo "session.cookie_samesite = Strict" >> /usr/local/etc/php/php.ini && \
    echo "session.use_strict_mode = 1" >> /usr/local/etc/php/php.ini && \
    echo "expose_php = Off" >> /usr/local/etc/php/php.ini && \
    echo "display_errors = Off" >> /usr/local/etc/php/php.ini && \
    echo "log_errors = On" >> /usr/local/etc/php/php.ini

COPY app/ /var/www/html/

# Permisos seguros
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
