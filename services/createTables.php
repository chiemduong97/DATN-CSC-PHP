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
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `email` VARCHAR(255) NOT NULL UNIQUE,
                    `password` VARCHAR(255) NOT NULL,
                    `avatar` VARCHAR(255),
                    `fullname` VARCHAR(255),
                    `phone` VARCHAR(255) NOT NULL UNIQUE,
                    `birthday` VARCHAR(255),
                    `wallet` REAL DEFAULT 0 NOT NULL,
                    `csc_point` REAL DEFAULT 0 NOT NULL,
                    `status` INT(1) DEFAULT 1 NOT NULL,
                    `permission` INT(1) DEFAULT 2 NOT NULL,
                    `first_order` BIT DEFAULT 0 NOT NULL,
                    `device_token` VARCHAR(255),
                    `created_at` DATETIME DEFAULT NOW() NOT NULL,
                    `lat` REAL DEFAULT 0 NOT NULL,
                    `long` REAL DEFAULT 0 NOT NULL,
                    `address` VARCHAR(255) NOT NULL
                    );
                    CREATE TABLE IF NOT EXISTS `categories` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL UNIQUE,
                    `avatar` VARCHAR(255) NOT NULL,
                    `status` INT(1) DEFAULT 1 NOT NULL,
                    `created_at` DATETIME DEFAULT NOW() NOT NULL,
                    `category_id` INT(11),
                    FOREIGN KEY (`category_id`) REFERENCES categories(`id`)
                    );
                    CREATE TABLE IF NOT EXISTS `branches` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL UNIQUE,
                    `avatar` VARCHAR(255) NOT NULL,
                    `lat` REAL NOT NULL,
                    `long` REAL NOT NULL,
                    `address` VARCHAR(255) NOT NULL,
                    `status` INT(1) DEFAULT 1 NOT NULL,
                    `created_at` DATETIME DEFAULT NOW() NOT NULL
                    );
                    CREATE TABLE IF NOT EXISTS `products` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL,
                    `avatar` VARCHAR(255) NOT NULL,
                    `description` VARCHAR(255) NOT NULL,
                    `price` REAL NOT NULL,
                    `created_at` DATETIME DEFAULT NOW() NOT NULL,
                    `updated_at` DATETIME,
                    `status` INT(1) DEFAULT 1 NOT NULL,
                    `category_id` INT(11) NOT NULL,
                    FOREIGN KEY (`category_id`) REFERENCES categories(`id`)
                    );
                    CREATE TABLE IF NOT EXISTS `warehouse` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `quantity` INT(11) NOT NULL,
                    `created_at` DATETIME DEFAULT NOW() NOT NULL,
                    `product_id` INT(11) NOT NULL,
                    `branch_id` INT(11) NOT NULL,
                    FOREIGN KEY (`product_id`) REFERENCES products(`id`),
                    FOREIGN KEY (`branch_id`) REFERENCES branches(`id`)
                    );
                    CREATE TABLE IF NOT EXISTS `promotions` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `avatar` VARCHAR(255) NOT NULL,
                    `description` VARCHAR(255) NOT NULL,
                    `code` VARCHAR(255) NOT NULL,
                    `value` REAL NOT NULL,
                    `status` BIT DEFAULT 1 NOT NULL,
                    `created_at` DATETIME DEFAULT NOW() NOT NULL,
                    `start` DATE NOT NULL,
                    `end` DATE NOT NULL
                    );
                    CREATE TABLE IF NOT EXISTS `orders` (
                    `order_code` VARCHAR(6) NOT NULL PRIMARY KEY,
                    `status` INT(1) DEFAULT 0 NOT NULL,
                    `amount` REAL NOT NULL,
                    `created_at` DATETIME DEFAULT NOW() NOT NULL,
                    `phone` VARCHAR(255) NOT NULL,
                    `lat` REAL NOT NULL,
                    `long` REAL NOT NULL,
                    `address` VARCHAR(255) NOT NULL,
                    `branch_lat` REAL NOT NULL,
                    `branch_long` REAL NOT NULL,
                    `branch_address` VARCHAR(255) NOT NULL,
                    `shipping_fee` REAL NOT NULL,
                    `promotion_code` VARCHAR(255),
                    `promotion_value` REAL,
                    `user_id` INT(11) NOT NULL,
                    `branch_id` INT(11) NOT NULL,
                    `promotion_id` INT(11),
                    FOREIGN KEY (`user_id`) REFERENCES users(`id`),
                    FOREIGN KEY (`branch_id`) REFERENCES branches(`id`),
                    FOREIGN KEY (`promotion_id`) REFERENCES promotions(`id`)
                    );
                    CREATE TABLE IF NOT EXISTS `order_details` (
                    `quantity` INT(11) NOT NULL,
                    `price` REAL NOT NULL,
                    `order_code` VARCHAR(6) NOT NULL,
                    `product_id` INT(11) NOT NULL,
                    FOREIGN KEY (`order_code`) REFERENCES orders(`order_code`),
                    FOREIGN KEY (`product_id`) REFERENCES products(`id`)
                    );
                    CREATE TABLE IF NOT EXISTS `requests` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `token` VARCHAR(255) NOT NULL,
                    `salt` VARCHAR(255) NOT NULL,
                    `created_at` DATETIME DEFAULT NOW() NOT NULL,
                    `status` BIT DEFAULT 1 NOT NULL,
                    `email` VARCHAR(255) NOT NULL,
                    FOREIGN KEY (`email`) REFERENCES users(`email`)
                    );
                    CREATE TABLE IF NOT EXISTS `ratings` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `rating` INT(1) NOT NULL,
                    `content` VARCHAR NOT NULL,
                    `created_at` DATETIME DEFAULT NOW() NOT NULL,
                    `user_id` INT(11) NOT NULL,
                    `product_id` INT(11) NOT NULL,
                    FOREIGN KEY (`user_id`) REFERENCES users(`id`),
                    FOREIGN KEY (`product_id`) REFERENCES products(`id`)
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