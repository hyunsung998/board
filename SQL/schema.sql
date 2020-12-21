CREATE DATABASE board;

USE board;

CREATE TABLE `board`.`users`(
	`id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) NOT NULL UNIQUE KEY,
  `password` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY(`id`));

CREATE TABLE `board`.`topics` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `created_at` TIMESTAMP NOT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES users(id));