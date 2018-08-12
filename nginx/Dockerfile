FROM nginx:latest
MAINTAINER bugwabara

ADD nginx/server.conf /etc/nginx/conf.d/server.conf

WORKDIR /var/www
COPY dist/src/public /var/www/public
