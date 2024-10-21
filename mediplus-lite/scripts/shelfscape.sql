-- Drop all tables
DROP TABLE IF EXISTS `User`;

-- Create User table
CREATE TABLE IF NOT EXISTS `User` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(50) NOT NULL UNIQUE,
    `phone` VARCHAR(50) NOT NULL UNIQUE,
    PRIMARY KEY (`id`)
);