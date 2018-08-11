ARG MYSQL_VERSION=5.7
FROM mysql:${MYSQL_VERSION}

LABEL maintainer="Bugwabara"

#####################################
# Set Timezone
#####################################

ARG TZ=UTC
# ENV MYSQL_DATABASE ${MYSQL_DATABASE}
# ENV MYSQL_USER ${MYSQL_USER}
# ENV MYSQL_PASSWORD ${MYSQL_PASSWORD}
# ENV MYSQL_ROOT_PASSWORD ${MYSQL_ROOT_PASSWORD}

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone && chown -R mysql:root /var/lib/mysql/

COPY my.cnf /etc/mysql/conf.d/my.cnf
COPY docker-entrypoint-initdb.d/createdb.sql /docker-entrypoint-initdb.d

CMD ["mysqld"]

EXPOSE 3306
