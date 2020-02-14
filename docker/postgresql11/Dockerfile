# https://hub.docker.com/_/postgres

FROM postgres:11-alpine

LABEL maintainer="Fernando Lamas <lamas.fernando1419@gmail.com>"

RUN apk update && \
    apk add openrc nano vim openssh bash && \
    rm -rf /var/cache/apk/*

COPY ./init-user-db.sh  /docker-entrypoint-initdb.d/init-user-db.sh

# Improving prompt data in bash shell
RUN sed -i -e "s/^export PS1/# export PS1/g" /etc/profile && \
    echo "export PS1='\u@\h:\w# '" | tee /root/.bashrc /home/.bashrc > /dev/null 2>&1

WORKDIR /var/lib/postgresql

EXPOSE 5432
