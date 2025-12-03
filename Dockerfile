FROM php:8.2-apache

# 1. Define the PORT variable (default is 8080 on Cloud Run)
ENV PORT 8080

# 2. Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 3. Configure Apache to listen on the $PORT environment variable
# a. Modify ports.conf to listen on the $PORT variable
RUN sed -i -e "s/Listen 80/Listen \${PORT}/g" /etc/apache2/ports.conf
# b. Modify the default VirtualHost to bind to the dynamic port
RUN sed -i -e "s/<VirtualHost \*:80>/<VirtualHost \*:\${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 4. Copy your THREE elements to document root
COPY application/ /var/www/html/application/
COPY public/ /var/www/html/public/
COPY .htaccess /var/www/html/

# 5. Enable Apache modules and set permissions
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

# 6. Create temporary directories for Cloud Run
RUN mkdir -p /tmp/cache /tmp/uploads /tmp/sessions
RUN chmod 755 /tmp/cache /tmp/uploads /tmp/sessions

# 7. EXPOSE is now optional, but can stay for documentation
EXPOSE 8080