FROM php:8.2-apache

# Copy all your PHP backend files into the web server directory
COPY . /var/www/html/

# Expose port 80 for Render
EXPOSE 80