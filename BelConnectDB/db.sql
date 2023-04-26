DROP database BelConnectDB;
Create database BelConnectDB;
use BelConnectDB;

Create table IF NOT exists Users(
	UserID int primary key auto_increment,
	Firstname varchar (200),
	Lastname varchar (200),
	Phone_number int not null,
	age int not null
);

Create table IF NOT exists accounts (
	AccountsID int primary key auto_increment,
	UserID int,
	Username varchar(200),
	Password varchar(200),
	Email varchar(200),
	image_type VARCHAR(255),
	image_data LONGBLOB,
	RegistrationDate DATETIME DEFAULT current_timestamp,
	FOREIGN KEY (UserID) REFERENCES Users(UserID)
);
DROP table tagS;
Create table if not exists Tags(
TagID int primary key auto_increment,
Tagname varchar(200)
);


insert into Tags ( Tagname) values
("Programing"),
("Food"),
("Art"),
("Music"),
("Other");
DROP TABLE POSTS;
Create table if not exists posts (
  PostId int primary key auto_increment,
  UserID int,
  Author VARCHAR(255) NOT NULL,
  Title VARCHAR(255) NOT NULL,
  Content TEXT NOT NULL,
  TagID int NOT NULL,
  Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (UserID) references Users(UserID),
  FOREIGN KEY (TagID) REFERENCES Tags(TagID)
);

CREATE TABLE user_secret_questions (
    user_secret_questions_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    Question VARCHAR(255) NOT NULL,
    Answer VARCHAR(255) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Profile_pic(
	Profile_picID int primary key auto_increment,
	UserID int,
	image_type VARCHAR(255),
	image_data LONGBLOB,
	FOREIGN KEY (UserID) REFERENCES Users (UserID)
);

Create table Post_Pic(
	Post_picID int primary key auto_increment,
	PostID int,
	image_type VARCHAR(255),
	image_data LONGBLOB
);


