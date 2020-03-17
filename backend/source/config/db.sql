CREATE DATABASE taskmanager
  CHARACTER SET utf8
  COLLATE utf8_general_ci;
  
USE taskmanager;

CREATE TABLE task(

id int not null auto_increment,
task varchar(50) not null,
description varchar(168) not null,
twhen date not null,
created_at timestamp not null default current_timestamp(),
updated_at timestamp not null default current_timestamp() on update current_timestamp(),
primary key(id)

)DEFAULT CHARSET = utf8;

SELECT * FROM task;