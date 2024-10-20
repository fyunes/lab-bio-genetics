CREATE database sistema_usuarios;
USE sistema_usuarios; 

CREATE TABLE usuarios (
id INT(11) auto_increment primary KEY,
nombre varchar (50) not null,
email varchar (50) not null unique,
password varchar (225) not null
);