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
                    `wallet` REAL DEFAULT 0 NOT NULL,
                    `status` BIT DEFAULT 1 NOT NULL,
                    `permission` int(1) DEFAULT 2 NOT NULL,
                    `firstorder` BIT DEFAULT 0 NOT NULL,
                    `devicetoken` VARCHAR(255),
                    `createdAt` DATETIME DEFAULT NOW() NOT NULL,
                    `latitude` REAL NOT NULL,
                    `longitude` REAL NOT NULL,
                    `address` VARCHAR(255) NOT NULL
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
                    `latitude` REAL NOT NULL,
                    `longitude` REAL NOT NULL,
                    `address` VARCHAR(255) NOT NULL,
                    `status` int(1) DEFAULT 1 NOT NULL
                );
                CREATE TABLE IF NOT EXISTS `products` (
                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL,
                    `avatar` VARCHAR(255) NOT NULL,
                    `description` VARCHAR(255) NOT NULL,
                    `price` REAL NOT NULL,
                    `createdAt` DATETIME DEFAULT NOW() NOT NULL,
                    `updatedAt` DATETIME,
                    `category_id` int(11) NOT NULL,
                    `status` int(1) DEFAULT 1 NOT NULL,
                    FOREIGN KEY (`category_id`) REFERENCES categories(`id`)
                );
                CREATE TABLE IF NOT EXISTS `quantities` (
                    `quantity` int(11) NOT NULL,
                    `product_id` int(11) NOT NULL,
                    `branch_id` int(11) NOT NULL,
                    FOREIGN KEY (`product_id`) REFERENCES products(`id`),
                    FOREIGN KEY (`branch_id`) REFERENCES branches(`id`)
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
                    `ordercode` VARCHAR(6) NOT NULL PRIMARY KEY,
                    `status` int(1) DEFAULT 0 NOT NULL,
                    `amount` REAL NOT NULL,
                    `createdAt` DATETIME DEFAULT NOW() NOT NULL,
                    `phone` VARCHAR(255) NOT NULL,
                    `latitude` REAL NOT NULL,
                    `longitude` REAL NOT NULL,
                    `address` VARCHAR(255) NOT NULL,
                    `branch_latitude` REAL NOT NULL,
                    `branch_longitude` REAL NOT NULL,
                    `branch_address` VARCHAR(255) NOT NULL,
                    `shippingFee` REAL NOT NULL,
                    `promotionCode` VARCHAR(255),
                    `promotionValue` REAL,
                    `user_id` int(11) NOT NULL,
                    `branch_id` int(11),
                    `promotion_id` int(11) NOT NULL,
                    FOREIGN KEY (`user_id`) REFERENCES users(`id`),
                    FOREIGN KEY (`branch_id`) REFERENCES branches(`id`),
                    FOREIGN KEY (`promotion_id`) REFERENCES promotions(`id`)
                );
                CREATE TABLE IF NOT EXISTS `order_details` (
                    `name` VARCHAR(255) NOT NULL,
                    `quantity` int(11) NOT NULL,
                    `amount` REAL NOT NULL,
                    `ordercode` VARCHAR(6) NOT NULL,
                    `product_id` int(11) NOT NULL,
                    FOREIGN KEY (`ordercode`) REFERENCES orders(`ordercode`),
                    FOREIGN KEY (`product_id`) REFERENCES products(`id`)
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