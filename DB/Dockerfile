FROM mysql:latest

COPY init.sql /docker-entrypoint-initdb.d/

ENV MYSQL_ROOT_PASSWORD="rootpassword"

CMD ["docker-entrypoint.sh", "mysqld"]

EXPOSE 3306