FROM nginx:1.15.2
MAINTAINER bugwabara

ADD nginx/server.conf /etc/nginx/conf.d/server.conf

WORKDIR /var/www
COPY dist/src/public /var/www/public
