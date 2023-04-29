DROP database BelConnectDB;
Create database BelConnectDB;
use BelConnectDB;

#tabel där alla användare sparas
Create table IF NOT exists Users(
	UserID int primary key auto_increment,
	Firstname varchar (200),
	Lastname varchar (200),
	Phone_number int not null,
	age int not null,
    Username varchar(200),
	Password varchar(200),
    Email varchar(200),
    RegistrationDate DATETIME DEFAULT current_timestamp
);

#tabel där alla taggs sparas
Create table if not exists Tags(
TagID int primary key auto_increment,
Tagname varchar(200)
);

#skapar de en gång så att varje post inte behöver en unik tag (undeviker att skapa extra data)
insert into Tags ( Tagname) values
("Programing"),
("Food"),
("Art"),
("Music"),
("Other");


#en tabel där jag sparar alla inlägg
Create table if not exists posts (
  PostId int primary key auto_increment,
  UserID int,
  Title VARCHAR(255) NOT NULL,
  Content TEXT NOT NULL,
  TagID int NOT NULL,
  Anonymous Boolean NOT NULL,
  Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (UserID) references Users(UserID),
  FOREIGN KEY (TagID) REFERENCES Tags(TagID)
);

#en tabel för secret questions
CREATE TABLE user_secret_questions (
    user_secret_questions_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    Question VARCHAR(255) NOT NULL,
    Answer VARCHAR(255) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

#en tabel för alla profil bilder
CREATE TABLE Profile_pic(
	Profile_picID int primary key auto_increment,
	UserID int,
	image_type VARCHAR(255),
	image_data LONGBLOB,
	FOREIGN KEY (UserID) REFERENCES Users (UserID)
);



