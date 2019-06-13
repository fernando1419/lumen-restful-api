# https://hub.docker.com/_/postgres

FROM postgres:12-alpine

LABEL maintainer="Fernando Lamas <lamas.fernando1419@gmail.com>"

RUN apk update && \
    apk add openrc nano vim openssh bash && \
    rm -rf /var/cache/apk/*

COPY ./create_database.sql  /docker-entrypoint-initdb.d/create_database.sql

# Improving prompt data in bash shell
RUN sed -i -e "s/^export PS1/# export PS1/g" /etc/profile && \
    echo "export PS1='\u@\h:\w$ '" | tee /root/.bashrc /home/.bashrc > /dev/null 2>&1

WORKDIR /var/lib/postgresql

EXPOSE 5432
