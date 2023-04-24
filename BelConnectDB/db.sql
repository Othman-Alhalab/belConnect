DROP database BelConnectDB;
Create database BelConnectDB;
use BelConnectDB;

Create table IF NOT exists Users(
    UserID int primary key auto_increment,
    Firstname VARCHAR(255),
    Lastname VARCHAR(255),
    Phone_number int not null,
    age int not null
);

CREATE TABLE IF NOT EXISTS Accounts (
    UserID INT NOT NULL,
    Username VARCHAR(255) PRIMARY KEY,
    Password VARCHAR(255),
    RegistrationDate DATETIME DEFAULT current_timestamp,
    Email varchar(200),
    image_type VARCHAR(255),
    image_data LONGBLOB,
    UNIQUE INDEX (Username)
);

Create table if not exists Tags(
    TagID int primary key auto_increment,
    Tags varchar(255) NOT NULL
);

Create table if not exists Posts (
    PostId int primary key auto_increment,
    UserID INT NOT NULL,
    Author VARCHAR(255) NOT NULL,
    Username VARCHAR(255) NOT NULL,
    Title VARCHAR(255) NOT NULL,
    Content TEXT NOT NULL,
    TagID INT,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (TagID) REFERENCES Tags(TagID)
);


CREATE TABLE user_secret_questions (
    user_secret_questions_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    Question VARCHAR(255) NOT NULL,
    Answer VARCHAR(255) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);
