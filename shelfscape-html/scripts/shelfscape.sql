CREATE DATABASE IF NOT EXISTS shelfscape;

CREATE TABLE IF NOT EXISTS 
`User`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `phone` VARCHAR(50) NOT NULL
);

-- todo, insert all book data into sql directly