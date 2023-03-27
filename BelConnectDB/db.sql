CREATE DATABASE BelConnectDB;

USE BelConnectDB;

CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL
);

CREATE TABLE `sessions` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `session_key` VARCHAR(255) NOT NULL,
  `expiration` DATETIME NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);


INSERT INTO `users` (`username`, `password`)
VALUES ('Admin', 'masterpass'),
       ('user2', 'password2_hashed'),
       ('user3', 'password3_hashed');