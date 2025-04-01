DROP DATABASE RedSocialDB;
CREATE DATABASE RedSocialDB;

USE RedSocialDB;

CREATE TABLE USUARI(
	userID INT PRIMARY KEY AUTO_INCREMENT,
	nomUsuari VARCHAR(16) UNIQUE,
	eMail VARCHAR(40) UNIQUE,
    passHash VARCHAR(60) ,
    userNom VARCHAR(60),
    userCognom VARCHAR(120),
    createDate DATETIME,
    removeDate DATETIME,
    lastLogin DATETIME,
    activationDate DateTime,
	activationCode Char(64),
	resetPassExpiry DateTime,
	resetPassCode Char(64),
    profilePic VARCHAR(255),   
    bio TEXT,             
    ubication VARCHAR(255),       
    birth DATE, 
    active TINYINT DEFAULT 0
);

CREATE TABLE PUBLICACIO(
	userID INT,
    idPost INT PRIMARY KEY AUTO_INCREMENT,
	post VARCHAR(2000),
    datePubisehd DATETIME,
    image VARCHAR(4000)    
);