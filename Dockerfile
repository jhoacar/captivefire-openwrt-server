FROM openwrtorg/rootfs:x86-64

# Install Server Dependencies 
RUN mkdir /var/lock && \
    opkg update && opkg install \
    uhttpd \
    php8 \
    php8-cgi \
    php8-cli \
    git \
    sudo \
    shadow-useradd \
    shadow-usermod \
    shadow-groupadd \
    shadow-su \
    unzip 

# Configuration php8-cli
RUN ln -s /usr/bin/php8-cli /usr/bin/php
    
# Install PHP Extensions ( Composer )
RUN opkg install \
    php8-mod-iconv \
    php8-mod-phar \
    php8-mod-mbstring \
    php8-mod-openssl \
    php8-mod-zip \
    php8-mod-filter \ 
    php8-mod-curl

# Install PHP Extensions ( Symfony )
RUN opkg install \
    php8-mod-xml \
    php8-mod-ctype \
    php8-mod-dom \
    php8-mod-xmlwriter \
    php8-mod-tokenizer


# Install Composer 
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer


# We need a user with the same UID/GID as the host user
# so when we execute CLI commands, all the host file's permissions and ownership remain intact.
# Otherwise commands from inside the container would create root-owned files and directories.
ARG UID=1000
ENV UID=$UID

ARG USERNAME=captivefire
ENV USERNAME=$USERNAME

ARG FOLDER=/app
ENV FOLDER=$FOLDER

ARG CONSOLE=/bin/bash
ENV CONSOLE=$CONSOLE

RUN mkdir $FOLDER

# Create an user with sudo privileges and bash console
RUN groupadd --system sudo
RUN useradd -G root -u $UID -d $FOLDER -s $CONSOLE $USERNAME
RUN usermod -a -G sudo $USERNAME
# Privileges in folder to user
RUN chown -R $USERNAME:$USERNAME $FOLDER

# New added for disable sudo password
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

# USER $USERNAME

# Its necessary a root user for run this container
USER root

WORKDIR $FOLDER

# Configuring a bash console like host
RUN opkg install bash;
COPY ./docker/bashrc /root/.bashrc

# Using exec format so that /sbin/init is proc 1 (see procd docs)
CMD ["/sbin/init"]