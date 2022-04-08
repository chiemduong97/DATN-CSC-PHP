<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/database.php';

    class CreateTables {
        private $db;
        
        public function __construct(){
            $this -> db = (new DatabaseConfig()) -> db_connect();
        }

        public function createTables(){
            try{
                $sql = "CREATE TABLE IF NOT EXISTS `users` (
                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `email` VARCHAR(255) NOT NULL UNIQUE,
                    `password` VARCHAR(255) NOT NULL,
                    `avatar` VARCHAR(255),
                    `fullname` VARCHAR(255),
                    `phone` VARCHAR(255) NOT NULL,
                    `birthday` VARCHAR(255),
                    `wallet` DECIMAL(10,2) DEFAULT 0 NOT NULL,
                    `status` BIT DEFAULT 1 NOT NULL,
                    `permission` int(1) DEFAULT 2 NOT NULL,
                    `firstorder` BIT DEFAULT 0 NOT NULL,
                    `devicetoken` VARCHAR(255),
                    `createdAt` DATETIME DEFAULT NOW() NOT NULL
                );
                CREATE TABLE IF NOT EXISTS `categories` (
                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL UNIQUE,
                    `avatar` VARCHAR(255) NOT NULL,
                    `status` int(1) DEFAULT 1 NOT NULL
                );
                CREATE TABLE IF NOT EXISTS `branches` (
                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL UNIQUE,
                    `latitude` DECIMAL(10,10) NOT NULL,
                    `longitude` DECIMAL(10,10) NOT NULL,
                    `address` VARCHAR(255) NOT NULL,
                    `status` int(1) DEFAULT 1 NOT NULL
                );
                CREATE TABLE IF NOT EXISTS `products` (
                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL,
                    `avatar` VARCHAR(255) NOT NULL,
                    `description` VARCHAR(255) NOT NULL,
                    `price` DECIMAL(10,2) NOT NULL,
                    `createdAt` DATETIME DEFAULT NOW() NOT NULL,
                    `updatedAt` DATETIME,
                    `category` int(11) NOT NULL,
                    `status` int(1) DEFAULT 1 NOT NULL,
                    FOREIGN KEY (`category`) REFERENCES categories(`id`)
                );
                CREATE TABLE IF NOT EXISTS `quantities` (
                    `quantity` int(11) NOT NULL,
                    `product` int(11) NOT NULL,
                    `branch` int(11) NOT NULL,
                    FOREIGN KEY (`product`) REFERENCES products(`id`),
                    FOREIGN KEY (`branch`) REFERENCES branches(`id`)
                );
                CREATE TABLE IF NOT EXISTS `promotions` (
                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `avatar` VARCHAR(255) NOT NULL,
                    `description` VARCHAR(255) NOT NULL,
                    `code` VARCHAR(255) NOT NULL,
                    `status` BIT DEFAULT 1 NOT NULL,
                    `createdAt` DATETIME DEFAULT NOW() NOT NULL,
                    `start` DATE NOT NULL,
                    `end` DATE NOT NULL
                );
                CREATE TABLE IF NOT EXISTS `orders` (
                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `ordercode` VARCHAR(6) NOT NULL,
                    `products` VARCHAR(255) NOT NULL,
                    `location` VARCHAR(255) NOT NULL,
                    `amount` DECIMAL(10,2) NOT NULL,
                    `createdAt` DATETIME DEFAULT NOW() NOT NULL,
                    `status` int(1) DEFAULT 0 NOT NULL,
                    `user` int(11) NOT NULL,
                    `branch` int(11) NOT NULL,
                    `promotion` int(11) NOT NULL,
                    FOREIGN KEY (`user`) REFERENCES users(`id`),
                    FOREIGN KEY (`branch`) REFERENCES branches(`id`),
                    FOREIGN KEY (`promotion`) REFERENCES promotions(`id`)
                );
                CREATE TABLE IF NOT EXISTS `requests` (
                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `email` VARCHAR(255) NOT NULL UNIQUE,
                    `token` VARCHAR(255) NOT NULL,
                    `salt` VARCHAR(255) NOT NULL,
                    `createdAt` DATETIME DEFAULT NOW() NOT NULL,
                    `available` BIT DEFAULT 1 NOT NULL
                );";
                $stmt = $this -> db -> prepare($sql);
            
                return $stmt -> execute();
            }catch(Exception $e){
                throw $e;
            }
            return false;
        }

    }


?>