# Use official PHP Apache image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html/

# Copy PHP source code
COPY . /var/www/html/

# Ensure index.php exists for directory root (optional health check)
RUN echo "<?php echo 'OK'; ?>" > index.php

# Enable Apache mod_rewrite (if needed for routing)
RUN a2enmod rewrite

# Set Apache DirectoryIndex to your main PHP endpoint
RUN echo "DirectoryIndex generate-upload-url.php" >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
