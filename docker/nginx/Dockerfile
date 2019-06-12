# https://hub.docker.com/_/nginx?tab=description

FROM nginx:1.17-alpine

LABEL maintainer="Fernando Lamas <lamas.fernando1419@gmail.com>"

RUN apk update && \
    apk add openrc nano vim openssh bash && \
    rm -rf /var/cache/apk/*

COPY ./nginx.conf /etc/nginx/nginx.conf

CMD sed -i -e 's/$PORT/'"$PORT"'/g' /etc/nginx/nginx.conf && \
    nginx -g 'daemon off;'

RUN chmod 644 /etc/nginx/nginx.conf

WORKDIR /usr/share/nginx/html

EXPOSE 80 443
