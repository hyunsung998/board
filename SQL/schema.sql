CREATE DATABASE board;

CREATE TABLE `board`.`users`(
	`id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(20) NOT NULL UNIQUE KEY,
    `password` TEXT NOT NULL,
    PRIMARY KEY(`id`));

CREATE TABLE `board`.`topics` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `created` TIMESTAMP NOT NULL,
  `deleted_at` TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`users_id`) REFERENCES users(id));