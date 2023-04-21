DROP DATABASE belconnectdb;

CREATE DATABASE belconnectdb;

USE belconnectdb;

CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  firstname VARCHAR(255) NOT NULL,
  lastname VARCHAR(255) NOT NULL,
  image_type VARCHAR(255),
  image_data LONGBLOB
);



CREATE TABLE sessions (
    session_id INT PRIMARY KEY,
    start_time TIME,
    end_time TIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE user_secret_questions (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  question VARCHAR(255) NOT NULL,
  answer VARCHAR(255) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
);


CREATE TABLE posts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  author VARCHAR(255) NOT NULL,
  username VARCHAR(255) NOT NULL,
  post_name VARCHAR(255) NOT NULL,
  post_data TEXT NOT NULL,
  tags VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



alter table users 
ADD image_type VARCHAR(255);
	
alter table users 
ADD image_data LONGBLOB;
