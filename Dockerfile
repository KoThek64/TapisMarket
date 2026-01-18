# Dockerfile pour Railway
ARG PHP_VERSION=8.4
FROM docker.io/library/php:${PHP_VERSION}-apache as builder

# Configuration Apache
COPY conteneur/app_php/data/apache2.conf conteneur/app_php/data/ports.conf /etc/apache2/

# Copie des sources (depuis la racine du repo)
COPY --chown=www-data:www-data src /var/www/html

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN <<EOF
export DEBIAN_FRONTEND=noninteractive
echo ----------------------------
echo "Mise à jour des dépôts"
echo ----------------------------
apt-get -q update
apt-get -y -q install unzip lsb-release ca-certificates curl apt-transport-https
curl -sSLo /tmp/debsuryorg-archive-keyring.deb https://packages.sury.org/debsuryorg-archive-keyring.deb
dpkg -i /tmp/debsuryorg-archive-keyring.deb
sh -c 'echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
sed -i "s/Pin-Priority:\ -1/Pin-Priority:\ 999/g" /etc/apt/preferences.d/no-debian-php
apt-get -q update

echo ----------------------------
echo "Installation de composer"
echo ----------------------------
curl -sS https://getcomposer.org/installer -o composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`
php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

echo ----------------------------
echo "Mise à jour du php.ini"
echo ----------------------------
cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

echo ----------------------------
echo "Installation des dépendances système pour les extensions"
echo ----------------------------
apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libcurl4-openssl-dev \
    unzip

echo ----------------------------
echo "Installation des extensions PHP"
echo ----------------------------
docker-php-ext-install -j$(nproc) \
    intl \
    mbstring \
    mysqli \
    pdo_mysql \
    gd

echo ----------------------------
echo "Installation des dépendances php"
echo ----------------------------
cd /var/www/html
composer install --no-interaction --optimize-autoloader

rm -f /var/www/html/.env
chown -R www-data:www-data /var/www/html

cat <<ENTRY > /var/www/html/entry.sh
#!/bin/sh
set -e
chown -R www-data:www-data /var/www/html/writable

# Run migrations automatiquement au démarrage
php /var/www/html/spark migrate --all || true

apache2-foreground
ENTRY

chmod +x /var/www/html/entry.sh

echo ----------------------------
echo "Configuration Apache"
echo ----------------------------
a2dissite 000-default || true
a2enmod rewrite

# Créer le virtual host pour CodeIgniter
cat <<VHOST > /etc/apache2/sites-available/ci.conf
<VirtualHost *:\${PORT:-8080}>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
VHOST

a2ensite ci.conf

# Configurer le port dynamique pour Railway
sed -i "s/Listen 80/Listen \${PORT:-8080}/g" /etc/apache2/ports.conf
sed -i "s/Listen 8080/Listen \${PORT:-8080}/g" /etc/apache2/ports.conf

EOF

EXPOSE 8080

ENTRYPOINT ["/var/www/html/entry.sh"]
CMD []
