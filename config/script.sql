CREATE DATABASE IF NOT EXISTS `datn_csc`;
USE `datn_csc`;
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255),
    `fullname` VARCHAR(255),
    `phone` VARCHAR(255) NOT NULL,
    `birthday` VARCHAR(255),
    `wallet` DECIMAL(10,2),
    `status` BIT DEFAULT 1 NOT NULL,
    `permission` int(1) DEFAULT 2,
    `firstorder` BIT DEFAULT 0 NOT NULL,
    `devicetoken` VARCHAR(255) NOT NULL,
    `createdAt` DATE DEFAULT NOW() NOT NULL
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
    `location` VARCHAR(255) NOT NULL,
    `status` int(1) DEFAULT 1 NOT NULL
);
CREATE TABLE IF NOT EXISTS `products` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `createdAt` DATE DEFAULT NOW() NOT NULL,
    `updatedAt` DATE NOT NULL,
    `status` int(1) DEFAULT 1 NOT NULL,
    `category` int(11) NOT NULL,
    FOREIGN KEY (`category`) REFERENCES categories(`id`)
);

CREATE TABLE IF NOT EXISTS `quantities` (
    `quantity` int(11) NOT NULL,
    `product` int(11) NOT NULL,
    `branch` int(11) NOT NULL,
    FOREIGN KEY (`product`) REFERENCES products(`id`),
    FOREIGN KEY (`branch`) REFERENCES branches(`id`)
);

CREATE TABLE IF NOT EXISTS `orders` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ordercode` VARCHAR(6) NOT NULL,
    `products` VARCHAR(255) NOT NULL,
    `location` VARCHAR(255) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `createdAt` DATE DEFAULT NOW() NOT NULL,
    `status` int(1) DEFAULT 0 NOT NULL,
    `user` int(11) NOT NULL,
    `branch` int(11) NOT NULL,
    `promotion` int(11) NOT NULL,
    FOREIGN KEY (`user`) REFERENCES users(`id`),
    FOREIGN KEY (`branch`) REFERENCES branches(`id`),
    FOREIGN KEY (`promotion`) REFERENCES promotions(`id`)
);
CREATE TABLE IF NOT EXISTS `promotions` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `avatar` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `status` BIT DEFAULT 1 NOT NULL,
    `createdAt` DATE DEFAULT NOW() NOT NULL,
    `start` DATE NOT NULL,
    `end` DATE NOT NULL
);

insert into `categories` ( `name`, `avatar`) values 
( 'Đồng hồ', 'https://cdn.pixabay.com/photo/2014/07/31/23/00/wristwatch-407096__340.jpg' ),
( 'Máy tính', 'https://cdn.pixabay.com/photo/2016/04/04/14/12/monitor-1307227__340.jpg'),
( 'Điện thoại', 'https://cdn.pixabay.com/photo/2014/07/31/23/00/wristwatch-407096__340.jpg'),
( 'Ti vi', 'https://cdn.pixabay.com/photo/2016/04/04/14/12/monitor-1307227__340.jpg'),
( 'Tủ lạnh', 'https://cdn.pixabay.com/photo/2014/07/31/23/00/wristwatch-407096__340.jpg'),
( 'Máy giặc', 'https://cdn.pixabay.com/photo/2016/04/04/14/12/monitor-1307227__340.jpg'),
( 'Đồ dùng nhà bếp', 'https://cdn.pixabay.com/photo/2014/07/31/23/00/wristwatch-407096__340.jpg'),
( 'Đồ gia dụng, dụng cụ', 'https://cdn.pixabay.com/photo/2016/04/04/14/12/monitor-1307227__340.jpg'),
( 'Máy lạnh, điều hòa', 'https://cdn.pixabay.com/photo/2014/07/31/23/00/wristwatch-407096__340.jpg')


insert into `branches` (`id`, `name`, `location`) values 
(null, 'Đồng hồ', 'https://cdn.pixabay.com/photo/2014/07/31/23/00/wristwatch-407096__340.jpg', null),
(null, 'Máy tính', 'https://cdn.pixabay.com/photo/2016/04/04/14/12/monitor-1307227__340.jpg', null)




