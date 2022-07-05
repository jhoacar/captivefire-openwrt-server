FROM openwrtorg/rootfs:x86-64

USER root

# Install Server Dependencies 
RUN mkdir /var/lock && \
    opkg update && opkg install \
    uhttpd \
    php8 \
    php8-cgi \
    php8-cli \
    git \
    unzip 

# Configuration php8-cli
RUN ln -s /usr/bin/php8-cli /usr/bin/php
    
# Install PHP Extensions
RUN opkg install \
    php8-mod-iconv \
    php8-mod-phar \
    php8-mod-mbstring \
    php8-mod-openssl \
    php8-mod-zip

# Install Composer 
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# using exec format so that /sbin/init is proc 1 (see procd docs)
CMD ["/sbin/init"]