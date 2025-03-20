CREATE TABLE Links (
    id int(11) auto_increment Primary Key,
    longUrl varchar(255),
    shortUrl varchar(255),
    countUse INT DEFAULT 0
);

