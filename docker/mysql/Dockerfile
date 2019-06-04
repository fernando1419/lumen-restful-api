# https://hub.docker.com/_/mysql
FROM mysql:8.0.16

LABEL maintainer="Fernando Lamas <lamas.fernando1419@gmail.com>"

#  debian:stretch-slim
RUN apt-get update && \
    apt-get install -y net-tools iputils-ping

COPY ./my.cnf /etc/mysql/conf.d/my.cnf

CMD ["mysqld"]

EXPOSE 3306
