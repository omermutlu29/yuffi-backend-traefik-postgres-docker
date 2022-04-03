FROM php:8.0-fpm
ARG user
ARG uid
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
RUN apt-get update && apt-get install -y \
    git \
    supervisor \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    vim \
    git
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd
#Laraver supervisor configuration
COPY laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf.d
CMD ["service supervisor reread"]
CMD ["service supervisor update"]
CMD ["service supervisor start"]


WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]



