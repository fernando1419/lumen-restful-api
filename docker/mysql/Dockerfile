# https://hub.docker.com/r/wangxian/alpine-mysql (10.1.38-MariaDB)
FROM alpine:3.7

LABEL maintainer="Fernando Lamas <lamas.fernando1419@gmail.com>"

RUN apk update && \
    apk add mysql mysql-client openrc nano vim openssh bash && \
    addgroup mysql mysql && \
    rm -rf /var/cache/apk/*

COPY ./my_alpine.cnf /etc/my.cnf

COPY ./startup.sh /startup.sh

RUN chmod +x /startup.sh && chown -R mysql:mysql /var/lib/mysql

WORKDIR /var/lib/mysql

EXPOSE 3306

CMD ["/startup.sh"]
