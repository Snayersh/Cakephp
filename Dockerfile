FROM php:8.2-apache

# Evitar prompts interactivos durante la instalación
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependencias del sistema requeridas para extensiones PHP y Oracle Instant Client
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libaio1t64 \
    libaio-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Asegurar symlink para libaio en Debian 13/Bookworm/Trixie
RUN if [ -f /usr/lib/x86_64-linux-gnu/libaio.so.1t64 ]; then \
        ln -sf /usr/lib/x86_64-linux-gnu/libaio.so.1t64 /usr/lib/x86_64-linux-gnu/libaio.so.1; \
    fi

# Configuración y descompresión de Oracle Instant Client 21.13
ENV ORACLE_HOME=/opt/oracle/instantclient
ENV LD_LIBRARY_PATH=/opt/oracle/instantclient

# Copiar archivos descargados localmente
RUN mkdir -p /opt/oracle
COPY docker/instantclient-basic.zip /opt/oracle/instantclient-basic.zip
COPY docker/instantclient-sdk.zip /opt/oracle/instantclient-sdk.zip
COPY docker/oci8-3.3.0.tgz /tmp/oci8.tgz

RUN unzip /opt/oracle/instantclient-basic.zip -d /opt/oracle/ && \
    unzip /opt/oracle/instantclient-sdk.zip -d /opt/oracle/ && \
    rm -f /opt/oracle/*.zip && \
    mv /opt/oracle/instantclient_21_13 /opt/oracle/instantclient && \
    echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient.conf && \
    ldconfig

# Compilar e instalar extensiones PHP: oci8 y pdo_oci
RUN echo "instantclient,/opt/oracle/instantclient" | pecl install /tmp/oci8.tgz && \
    docker-php-ext-enable oci8 && \
    rm -f /tmp/oci8.tgz && \
    docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient && \
    docker-php-ext-install -j$(nproc) pdo_oci

# Compilar e instalar extensiones estándar requeridas por CakePHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    intl \
    gd \
    zip

# Instalar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Habilitar mod_rewrite en Apache
RUN a2enmod rewrite

# Configurar Apache DocumentRoot hacia /var/www/html/webroot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/webroot
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    echo '<Directory /var/www/html/webroot>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/apache2.conf

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar script de arranque
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
