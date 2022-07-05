FROM openwrtorg/rootfs:x86-64

RUN mkdir /var/lock && \
    opkg update && \
    opkg install uhttpd-mod-lua luci luci-ssl && \
    uci set uhttpd.main.interpreter='.lua=/usr/bin/lua' && \
    uci commit uhttpd

USER root

# using exec format so that /sbin/init is proc 1 (see procd docs)
CMD ["/sbin/init"]