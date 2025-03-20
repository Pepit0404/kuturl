FROM mysql:latest

RUN chown -R mysql:root /var/lib/mysql/

COPY init.sql /docker-entrypoint-initdb.d/

ENV MYSQL_DATABASE=kuturl
ENV MYSQL_USER=kuturl
ENV MYSQL_PASSWORD=shorterurl
ENV MYSQL_ROOT_PASSWORD=rootpassword

RUN echo "CREATE USER '$MYSQL_USER'@'localhost' IDENTIFIED BY '$MYSQL_PASSWORD'; \
GRANT ALL PRIVILEGES ON $MYSQL_DATABASE.* TO '$MYSQL_USER'@'localhost'; \
FLUSH PRIVILEGES; \
" >> /docker-entrypoint-initdb.d/init.sql

EXPOSE 3306

RUN cat /docker-entrypoint-initdb.d/init.sql

CMD ["docker-entrypoint.sh", "mysqld"]
