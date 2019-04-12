FROM nginx:latest

MAINTAINER Fernando Lamas <lamas.fernando1419@gmail.com>

RUN apt-get update && \
    apt-get install -y build-essential && \
    apt-get install -y dnsutils curl openssh-client openssh-server nano screen vim rsync traceroute \
    iptables info lsof mc usbutils tcpdump time telnet ssl-cert bash-completion \
    binutils bc patch curl wget htop iputils-ping net-tools apt-utils && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /etc/nginx/conf.d/*.conf /etc/nginx/nginx.conf

COPY ./server.conf /etc/nginx/conf.d/server.conf

CMD sed -i -e 's/$PORT/'"$PORT"'/g' /etc/nginx/conf.d/server.conf && nginx -g 'daemon off;'
# ["nginx", "-g", "daemon off;"]

WORKDIR /usr/share/nginx/html

EXPOSE 80 443
