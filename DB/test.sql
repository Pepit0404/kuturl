Create Table Links (
    id int(11) auto_increment Primary Key
    longUrl varchar(255),
    shortUrl varchar(255),
    count int(11) default 0,
);

Create User 'kuturl'@'localhost' Identified By 'shorterurl';
Grant All On kuturl.* To 'kuturl'@'localhost';
Flush Privileges;