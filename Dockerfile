FROM openwrtorg/rootfs:x86-64

# Install Server Dependencies 
RUN mkdir /var/lock && \
    opkg update && opkg install \
    uhttpd \
    php8 \
    php8-cgi \
    luci

# Install PHP Extensions ( Necessary )
RUN opkg install \
    php8-mod-iconv \
    php8-mod-mbstring \
    php8-mod-curl \
    php8-mod-zip

ARG FOLDER=/app/
ENV FOLDER=$FOLDER

RUN mkdir $FOLDER

# Its necessary a root user for run this container
USER root

WORKDIR $FOLDER

# Questions for generate self-signed openssl certificate

# 1 - Country Name (2 letter code) [AU]:AR
ARG COUNTRYCODE=AR
ENV COUNTRYCODE=$COUNTRYCODE

# 2 - State or Province Name (full name) [Some-State]:Buenos Aires
ARG STATE=Buenos Aires
ENV STATE=$STATE

# 3 - Locality Name (eg, city) []:Buenos Aires
ARG LOCATION=Buenos Aires
ENV LOCATION=$LOCATION

# 4 - Organization Name (eg, company) [Internet Widgits Pty Ltd]:captivefire
ARG COMPANYNAME=captivefire
ENV COMPANYNAME=$COMPANYNAME

# 5 - Organizational Unit Name (eg, section) []:
ARG COMPANYSECTION=captivefire
ENV COMPANYSECTION=$COMPANYSECTION

# 6 - Common Name (e.g. server FQDN or YOUR name) []:
ARG COMMONNAME=local.router.captivefire.net
ENV COMMONNAME=$COMMONNAME

# 7 - Email Address []: captivefire@captivefire.net
ARG COMPANYEMAIL=captivefire@captivefire.net
ENV COMPANYEMAIL=$COMPANYEMAIL

# Generate SSL Certificate
# RUN opkg install openssl-util
# RUN openssl req -newkey rsa:4096 \
#         -x509 \
#         -sha256 \
#         -days 3650 \
#         -nodes \
#         -out $FOLDER$COMPANYNAME.crt \
#         -keyout $FOLDER$COMPANYNAME.key \
#             << EOF \
#             $COUNTRYCODE \
#             $STATE \
#             $LOCATION \
#             $COMPANYNAME \
#             $COMPANYSECTION \
#             $COMMONNAME \
#             $COMPANYEMAIL \
#             EOF

# Using exec format so that /sbin/init is proc 1 (see procd docs)
CMD ["/sbin/init"]