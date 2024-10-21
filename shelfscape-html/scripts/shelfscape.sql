CREATE DATABASE IF NOT EXISTS shelfscape;

DROP TABLE `User`;
CREATE TABLE IF NOT EXISTS 
`User`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(50) NOT NULL UNIQUE,
    `phone` VARCHAR(50) NOT NULL UNIQUE
);

-- todo, insert all book data into sql directly