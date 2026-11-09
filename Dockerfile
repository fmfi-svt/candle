FROM debian:bookworm-slim

RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates curl locales \
    && sed -i 's/^# *en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen \
    && locale-gen en_US.UTF-8 \
    && curl -fsSLo /tmp/debsuryorg-archive-keyring.deb \
        https://packages.sury.org/debsuryorg-archive-keyring.deb \
    && dpkg -i /tmp/debsuryorg-archive-keyring.deb \
    && rm /tmp/debsuryorg-archive-keyring.deb \
    && echo 'deb [signed-by=/usr/share/keyrings/debsuryorg-archive-keyring.gpg] https://packages.sury.org/php/ bookworm main' \
        > /etc/apt/sources.list.d/php.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        apache2 \
        libapache2-mod-php5.6 \
        php5.6-cli \
        php5.6-mbstring \
        php5.6-mysql \
        php5.6-xml \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Only web/ is public; Symfony's configuration and libraries stay outside the document root.
RUN printf '%s\n' \
    '<VirtualHost *:80>' \
    '    DocumentRoot /var/www/html/web' \
    '    # Trust identity supplied by the host authentication proxy.' \
    '    # This backend must not be directly reachable by untrusted clients.' \
    '    SetEnvIf X-Candle-User "^(.+)$" REMOTE_USER=$1' \
    '    <Directory /var/www/html/web>' \
    '        Options -Indexes +FollowSymLinks' \
    '        AllowOverride None' \
    '        Require all granted' \
    '        RewriteEngine On' \
    '        RewriteRule ^(index|index2|frontend_dev|kiosk)\.php(/|$) - [L]' \
    '        RewriteRule ^prihlasit/?$ index.php [QSA,L]' \
    '        RewriteRule ^$ index.html [QSA]' \
    '        RewriteRule ^([^.]+)$ $1.html [QSA]' \
    '        RewriteCond %{REQUEST_FILENAME} !-f' \
    '        RewriteRule ^(.*)$ index2.php [QSA,L]' \
    '    </Directory>' \
    '    ErrorLog /proc/self/fd/2' \
    '    CustomLog /proc/self/fd/1 combined' \
    '</VirtualHost>' \
    > /etc/apache2/sites-available/000-default.conf

COPY . .

RUN mkdir -p cache log \
    && chown -R www-data:www-data cache log

EXPOSE 80
CMD ["/bin/bash", "-c", "source /etc/apache2/envvars && mkdir -p \"$APACHE_RUN_DIR\" \"$APACHE_LOCK_DIR\" && rm -f \"$APACHE_PID_FILE\" && exec apache2 -D FOREGROUND"]
