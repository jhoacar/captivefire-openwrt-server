FROM openwrtorg/rootfs:x86-64

ENV PHP_VERSION=8

# Install Server Dependencies 
RUN mkdir /var/lock && \
    opkg update && opkg install \
    uhttpd \
    uhttpd-mod-lua \
    luci \
    luci-ssl \
    php$PHP_VERSION \
    php$PHP_VERSION-cgi

# uhttpd	2021-03-21-15346de8-2	              ~31.6 KB
# uhttpd-mod-ubus	2021-03-21-15346de8-2	      ~9.5 KB

# php8	8.0.20-1	                              ~2.5 KB
# php8-cgi	8.0.20-1	                          ~1.3 MB

# luci	git-20.074.84698-ead5e81	              ~1.0 KB
# luci-app-firewall	git-22.089.67741-3856d50	  ~15.0 KB
# luci-app-opkg	git-22.154.41894-1cf976c	      ~9.3 KB
# luci-base	git-22.213.35964-87836ca	          ~133.2 KB
# luci-lib-base	git-20.232.39649-1f6dc29	      ~13.3 KB
# luci-lib-ip	git-20.250.76529-62505bd	      ~13.5 KB
# luci-lib-jsonc	git-22.097.61937-bc85ba5	  ~5.2 KB
# luci-lib-nixio	git-20.234.06894-c4a4e43	  ~34.6 KB
# luci-mod-admin-full	git-19.253.48496-3f93650  ~959 B	
# luci-mod-network	git-22.046.85061-dd54dce	  ~47.1 KB
# luci-mod-status	git-22.046.85784-0ac2542	  ~29.9 KB
# luci-mod-system	git-22.130.00635-21f99bd	  ~18.8 KB
# luci-proto-ipv6	git-21.148.49484-14511e5	  ~3.5 KB
# luci-proto-ppp	git-21.163.64918-6c6559a	  ~3.0 KB
# luci-theme-bootstrap	git-22.084.39047-f1d687e  ~16.5 KB
# liblucihttp-lua	2021-06-11-3dc89af4-1	      ~4.2 KB
# liblucihttp0	2021-06-11-3dc89af4-1	          ~8.6 KB
# rpcd-mod-luci	20210614	                      ~15.5 KB
# luci-ssl

# Install PHP Extensions ( Necessary )

RUN opkg install \
    php$PHP_VERSION-mod-iconv \
    php$PHP_VERSION-mod-mbstring \
    php$PHP_VERSION-mod-curl \
    php$PHP_VERSION-mod-zip \
    php$PHP_VERSION-mod-phar \
    php$PHP_VERSION-mod-filter \

# php8-mod-iconv	8.0.20-1	                    ~18.5 KB
# php8-mod-mbstring	8.0.20-1	                    ~557.5 KB
# php8-mod-curl	8.0.20-1	                        ~33.3 KB
# php8-mod-zip	8.0.20-1	                        ~23.0 KB
# php8-mod-phar	8.0.20-1	                        ~100.2 KB

# Total ~2.4 MB

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