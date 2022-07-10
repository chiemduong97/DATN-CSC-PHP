CREATE DATABASE IF NOT EXISTS `datn_csc`;
USE `datn_csc`;
CREATE TABLE IF NOT EXISTS `users` (
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
`lng` REAL DEFAULT 0 NOT NULL,
`address` VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS `categories` (
`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`name` VARCHAR(255) NOT NULL,
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
`lng` REAL NOT NULL,
`address` VARCHAR(255) NOT NULL,
`status` INT(1) DEFAULT 1 NOT NULL,
`created_at` DATETIME DEFAULT NOW() NOT NULL
);
CREATE TABLE IF NOT EXISTS `products` (
`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`name` VARCHAR(255) NOT NULL,
`avatar` VARCHAR(255) NOT NULL,
`description` VARCHAR(1024) NOT NULL,
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
FOREIGN KEY (`product_id`) REFERENCES products(`id`)
);
CREATE TABLE IF NOT EXISTS `promotions` (
`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`avatar` VARCHAR(255) NOT NULL,
`description` VARCHAR(1024) NOT NULL,
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
`lng` REAL NOT NULL,
`address` VARCHAR(255) NOT NULL,
`branch_lat` REAL NOT NULL,
`branch_lng` REAL NOT NULL,
`branch_address` VARCHAR(255) NOT NULL,
`shipping_fee` REAL NOT NULL,
`promotion_code` VARCHAR(255),
`promotion_value` REAL,
`payment_method` VARCHAR(255) NOT NULL,
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
`name` VARCHAR(255) NOT NULL,
FOREIGN KEY (`order_code`) REFERENCES orders(`order_code`),
FOREIGN KEY (`product_id`) REFERENCES products(`id`)
);
CREATE TABLE IF NOT EXISTS `requests` (
`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`token` VARCHAR(255) NOT NULL,
`salt` VARCHAR(255) NOT NULL,
`created_at` DATETIME DEFAULT NOW() NOT NULL,
`status` BIT DEFAULT 1 NOT NULL,
`email` VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS `ratings` (
`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`rating` INT(1) NOT NULL,
`content` VARCHAR(1025) NOT NULL,
`created_at` DATETIME DEFAULT NOW() NOT NULL,
`user_id` INT(11) NOT NULL,
`product_id` INT(11) NOT NULL,
FOREIGN KEY (`user_id`) REFERENCES users(`id`),
FOREIGN KEY (`product_id`) REFERENCES products(`id`)
);

insert into `categories` ( `name`, `avatar`) values 
( 'Đồ uống - Giải khát', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1912-1653560355?w=60&type=s'),
( 'Snack - Đồ ăn vặt', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1905-1653560426?w=60&type=s'),
( 'Bánh - Kẹo', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1906-1653560288?w=60&type=s'),
( 'Sữa - Trứng', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1918-1653560437?w=60&type=s'),
( 'Nguyên liệu chế biến', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1910-1653560382?w=60&type=s'),
( 'Thực phẩm khô', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1916-1653560454?w=60&type=s'),
( 'Trà - Cà Phê', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1915-1653560487?w=60&type=s'),
( 'Chăm sóc cá nhân', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1913-1653560329?w=60&type=s'),
( 'Hóa phẩm', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1919-1653560371?w=60&type=s'),
( 'Đồ dùng gia đình', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1914-1653560342?w=60&type=s'),
( 'Sản phẩm nhập khẩu', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1954-1653560413?w=60&type=s'),
( 'Sản phẩm giấy', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1920-1653560400?w=60&type=s'),
( 'Thú cưng', 'https://tea-3.lozi.vn/v1/images/resized/category-mobile-1917-1653560468?w=60&type=s');


insert into `categories` ( `category_id`, `name`) values 
(1,'Nước ngọt'),
(1,'Nước ngọt chai'),
(1,'Nước trái cây'),
(1,'Nước suối - nước khoáng'),
(1,'Các loại đồ uống khác'),
(1,'Trà & cafe'),
(1,'Đồ uống pha bột'),
(1,'Bia'),
(1,'Rượu'),
(1,'Nước tăng lực');

insert into `categories` ( `category_id`, `name`) values 
(2,'Các loại snack'),
(2,'Khoai tây chiên'),
(2,'Snack rong biển'),
(2,'Các loại khô'),
(2,'Hạt và trái cây sấy'),
(2,'Sản phẩm ăn vặt khác');

insert into `categories` ( `category_id`, `name`) values 
(3,'Bánh gạo'),
(3,'Bánh'),
(3,'Ngũ cốc'),
(3,'Kẹo');

insert into `categories` ( `category_id`, `name`) values 
(4,'Sữa chua'),
(4,'Sữa đặc'),
(4,'Sữa hạt'),
(4,'Sữa tiệt trùng'),
(4,'Sữa đậu nành'),
(4,'Bánh flan'),
(4,'Trứng');

insert into `categories` ( `category_id`, `name`) values 
(5,'Dầu ăn'),
(5,'Gia vị - nước chấm'),
(5,'Các loại xốt'),
(5,'Các nguyên liệu khác'),
(5,'Đường'),
(5,'Bột - hạt nêm');

insert into `categories` ( `category_id`, `name`) values 
(6,'Mì - bún - phở'),
(6,'Nui'),
(6,'Topokki'),
(6,'Mì Hàn Quốc'),
(6,'Cháo gói'),
(6,'Thực phẩm ăn liền'),
(6,'Sản phẩm khô khác'),
(6,'Gạo & ngũ cốc'),
(6,'Đồ hộp');


insert into `categories` ( `category_id`, `name`) values 
(7,'Cà phê'),
(7,'Trà');

insert into `categories` ( `category_id`, `name`) values 
(8,'Dầu gội - xả cho nữ'),
(8,'Sản phẩm danh cho nam'),
(8,'Sữa tắm'),
(8,'Sản phẩm tình dục'),
(8,'Sản phẩm danh cho nữ'),
(8,'Chăm sóc răng miệng'),
(8,'Dao - bọt cạo'),
(8,'Bông tăm'),
(8,'Chăm sóc da'),
(8,'Sản phẩm chăm sóc khác'),
(8,'Tăm - chỉ nha khoa');

insert into `categories` ( `category_id`, `name`) values 
(9,'Giặt - xả'),
(9,'Sản phẩm dùng cho nhà bếp'),
(9,'Sản phẩm dùng cho nhà vệ sinh'),
(9,'Sản phẩm hóa phẩm khác');

insert into `categories` ( `category_id`, `name`) values 
(10,'Màn bọc - giấy - túi rác'),
(10,'Bình nước - ly - cốc'),
(10,'Đồ nhựa - thủy tinh'),
(10,'Đồ dùng gia đình khác'),
(10,'Dụng cụ vệ sinh');


insert into `categories` ( `category_id`, `name`) values 
(11,'Các loại snack nhập khẩu'),
(11,'Các loại nước nhập khẩu'),
(11,'Bánh nhập khẩu'),
(11,'Mức - bơ - đậu phộng'),
(11,'Nguyên liệu nhập khẩu'),
(11,'Đồ hộp nhập khẩu'),
(11,'Hóa phẩm nhập khẩu'),
(11,'Sản phẩm nhập khẩu khác');

insert into `categories` ( `category_id`, `name`) values 
(12,'Giấy khô - ướt'),
(12,'Giấy vệ sinh');

insert into `categories` ( `category_id`, `name`) values 
(13,'Thức ăn cho chó'),
(13,'Thức ăn cho mèo');

insert into `branches` (`name`, `address`, `lat`, `lng`) values 
('CSC Cống Quỳnh','189C Cống Quỳnh, Phường Nguyễn Cư Trinh, Quận 1, Thành phố Hồ Chí Minh',10.7675955,106.6837364),
('CSC Nguyễn Đình Chiểu', '168 Nguyễn Đình Chiểu, Phường 6, Quận 3, Thành phố Hồ Chí Minh',10.7814976,106.6902254),
('CSC Lý Thường Kiệt', '497 Hoà Hảo, Phường 7, Quận 10, Thành phố Hồ Chí Minh',10.7603773,106.6600321),
('CSC Nguyễn Kiệm', '573 Nguyễn Kiệm, Phường 9, Phú Nhuận, Thành phố Hồ Chí Minh',10.8053797,106.6763258),
('CSC Chu Văn An', '241A Chu Văn An, Phường 12, Bình Thạnh, Thành phố Hồ Chí Minh',10.8108285,106.7005714),
('CSC TTTM Thắng Lợi', '2 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh',10.8064163,106.6326443),
('CSC Pearl Plaza Văn Thánh', 'Tòa nhà Pearl Plaza, 561A Điện Biên Phủ, Phường 25, Bình Thạnh, Thành phố Hồ Chí Minh',10.8000066,106.7163559),
('CSC Hùng Vương', '96 Đ. Hùng Vương, Phường 9, Quận 5, Thành phố Hồ Chí Minh',10.7594821,106.6692745),
('CSC Hoà Bình', '175 Hòa Bình, Hiệp Tân, Tân Phú, Thành phố Hồ Chí Minh',10.770335,106.6270338),
('CSC Phú Thọ', 'Chung cư Phú Thọ - Khu B, Khu A, Nguyễn Thị Nhỏ, Phường 15, Quận 11, Thành phố Hồ Chí Minh',10.7717418,106.651165),
('CSC Tô Ký', '557 Tô Ký, Trung Mỹ Tây, Quận 12, Thành phố Hồ Chí Minh',10.8596653,106.6150525),
('CSC Đồng Văn Cống', '125 Đồng Văn Cống, Phường Thạnh Mỹ Lợi, Quận 2, Thành phố Hồ Chí Minh',10.7741357,106.7600982),
('CSC SCA Phạm Văn Chiêu', '359 Phạm Văn Chiêu, Phường 14, Gò Vấp, Thành phố Hồ Chí Minh',10.8513324,106.651176),
('CSC Xa Lộ Hà Nội', '191 Quang Trung, Hiệp Phú, Thành Phố Thủ Đức, Thành phố Hồ Chí Minh',10.8481369,106.7722614),
('CSC Huỳnh Tấn Phát', '1362 Huỳnh Tấn Phát, Phú Mỹ, Quận 7, Thành phố Hồ Chí Minh',10.7121174,106.73512),
('CSC Nhiêu Lộc', 'Tầng trệt cao ốc SCREC, Số 974A TP, Trường Sa, Phường 12, Quận 3',10.7865548,106.6731937),
('CSC Rạch Miễu', '48 Hoa Sứ, Phường 7, Phú Nhuận, Thành phố Hồ Chí Minh',10.7988598,106.687093),
('CSC Phan Văn Hớn', '102 Phan Văn Hớn, Tân Thới Nhất, Quận 12, Thành phố Hồ Chí Minh',10.828388,106.6182151);


insert into promotions (code, value, start, end) VALUES ('PROMOTION1',10000,'2022-07-01','2022-07-31');
insert into promotions (code, value, start, end) VALUES ('PROMOTION2',20000,'2022-07-01','2022-07-31');
insert into promotions (code, value, start, end) VALUES ('PROMOTION3',30000,'2022-07-01','2022-07-31');
insert into promotions (code, value, start, end) VALUES ('PROMOTION4',40000,'2022-07-01','2022-07-31');
insert into promotions (code, value, start, end) VALUES ('PROMOTION5',50000,'2022-07-01','2022-07-31');
insert into promotions (code, value, start, end) VALUES ('PROMOTION6',60000,'2022-07-01','2022-07-31');
insert into promotions (code, value, start, end) VALUES ('PROMOTION7',70000,'2022-07-01','2022-07-31');


