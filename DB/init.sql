CREATE DATABASE IF NOT EXISTS kuturl;
USE kuturl;

CREATE USER IF NOT EXISTS 'kuturl'@'%' IDENTIFIED BY 'shorterurl';
GRANT ALL ON kuturl.* TO 'kuturl'@'%';
FLUSH PRIVILEGES;


CREATE TABLE Links (
    id int(11) auto_increment Primary Key,
    longUrl varchar(255),
    shortUrl varchar(255),
    countUse INT DEFAULT 0
);

