CREATE DATABASE board;

CREATE TABLE topic(
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    description TEXT NOT NULL,
    created TIMESTAMP NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO topic (title,description,created) VALUES("css" , "css is...." , NOW()),("css" , "css is...." , NOW()),("html" , "html is...." , NOW()),("html" , "html is...." , NOW()),("css" , "css is...." , NOW());