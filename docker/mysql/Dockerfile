FROM mysql:latest

RUN apt-get update && \
    apt-get install -y net-tools iputils-ping

COPY ./my.cnf /etc/mysql/conf.d/my.cnf

CMD ["mysqld"]

EXPOSE 3306
