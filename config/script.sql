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


insert into `products` ( `name`, `avatar`,`description`,`price`,`category_id`) values 

( 
'Nước Ngọt Coca Cola Lon 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-coca-cola-lon-320ml-1656736676?w=960&type=s',
'Là loại nước ngọt được nhiều người yêu thích với hương vị thơm ngon, sảng khoái. Nước ngọt Coca Cola 320ml với lượng gas lớn sẽ giúp bạn xua tan mọi cảm giác mệt mỏi, căng thẳng, đem lại cảm giác thoải mái sau khi hoạt động ngoài trời',
12000, 14
),
( 
'Nước Ngọt Fanta Nho 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-fanta-nho-320ml-1653362921?w=960&type=s',
'- Nước giải khát hương nho Fanta chứa nhiều vitamin C sẽ cung cấp năng lượng cho cơ thể trong một ngày dài hoạt động. Sản phẩm được làm từ nước ép nguyên chất, có hương vị thơm ngon và hấp dẫn, tự nhiên.',
9500, 14
),
( 
'Nước Ngọt Pepsi Lon 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-pepsi-lon-320ml-1656736705?w=960&type=s',
'Từ thương hiệu nước ngọt có gas nổi tiếng toàn cầu với mùi vị thơm ngon với hỗn hợp hương tự nhiên cùng chất tạo ngọt tổng hợp, giúp xua tan cơn khát và cảm giác mệt mỏi. Nước ngọt bổ sung năng lượng làm việc mỗi ngày. Cam kết sản phẩm chính hãng, chất lượng và an toàn',
12000, 14
),
( 
'Nước Ngọt Soda Chương Dương 330ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-soda-chuong-duong-330ml-1653362989?w=960&type=s',
'Là thức uống giải khát từ thương hiệu Chương Dương giúp bổ sung vitamin và khoáng chất tốt cho cơ thể, sản phẩm là cách nhanh chóng để bù nước cho cơ thể khi vận động nhiều hoặc chơi thể thao. Soda Chương Dương lon 330ml cam kết không chứa chất hóa học độc hại, đảm bảo an toàn cho sức khỏe',
9000, 14
),
( 
'Thùng Nước Ngọt Pepsi 320ml 24 Lon - 1 Thùng',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-thung-nuoc-ngot-pepsi-320ml-24-lon-1656738121?w=960&type=s',
'Từ thương hiệu nước ngọt có gas nổi tiếng toàn cầu với mùi vị thơm ngon với hỗn hợp hương tự nhiên cùng chất tạo ngọt tổng hợp, giúp xua tan cơn khát và cảm giác mệt mỏi.  Nước ngọt bổ sung năng lượng làm việc mỗi ngày. Cam kết sản phẩm chính hãng, chất lượng và an toàn
Sử dụng: Ngon hơn khi uống lạnh',
235000, 14
),
( 
'Nước Ngọt Coca Cola Lon 235ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-coca-cola-lon-235ml-1653363191?w=960&type=s',
'Từ thương hiệu loại nước giải khát Coca Cola được nhiều người yêu thích với hương vị thơm ngon, sảng khoái. Nước ngọt Coca Cola 235ml với lượng gas lớn sẽ giúp bạn xua tan mọi cảm giác mệt mỏi, căng thẳng, đem lại cảm giác thoải mái sau khi hoạt động ngoài trời.
Ngon hơn khi uống lạnh',
9000, 14
),
( 
'Nước Ngọt Coca Light 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-coca-light-320ml-1655362112?w=300&type=s',
'Từ thương hiệu nước giải khát Coca Cola nổi tiếng thế giới được ưa chuộng tại nhiều nhiều quốc gia. Nước ngọt không đường Coca Cola Light lon 320ml là dòng sản phẩm nước uống có ga không đường, dành cho người ăn kiêng thoải mái với niềm đam mê nước ngọt mà không lo tăng cân
Thành phần: Nước bão hòa CO2, màu thực phẩm, chất điều chỉnh độ axit, chất tạo ngọt tổng hợp, chất bảo quản, hương cola tự nhiên và caffein.',
12000, 14
),
( 
'Nước Ngọt 7up Ít Calo Bổ Sung Chất Xơ 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-7up-it-calo-bo-sung-chat-xo-320ml-1656950733?w=960&type=s',
'Nước ngọt thơm ngon bổ sung năng lượng suốt ngày dài của 7 up là sự lựa chọn tuyệt vời cho bạn. Nước ngọt 7 Up ít calo bổ sung chất xơ lon 320ml thơm ngon, ít calo và đặc biệt giàu chất xơ tốt cho sức khỏe. Thành phần an toàn đã được kiểm nghiệm nên bạn có thể yên tâm sử dụng.
Thành phần Nước bão hòa CO2, chất xơ hòa tan (13g/L), chất điều chỉnh đọ axit (330,331)(ii) (296), hương chanh tự nhiên, chất tạo ngọt tổng hợp (Aspartam 951, Sucrlose (Triclorofalacto sucose ) 955, Acesulfam kali 950), chất bảo quản (211)',
11500, 14
),
(
'Nước Ngọt Sprite Lon 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-sprite-lon-320ml-1653708352?w=960&type=s',
'Sản phẩm có lượng gas lớn giúp xua tan mọi cảm giác mệt mỏi, căng thẳng ngay tức thì, đem lại cảm giác thoải mái nhất sau mỗi lần sử dụng và còn giúp bạn lấy lại năng lượng cho hoạt động hàng ngày.
Hương chanh thơm mát cùng sự kết hợp với các vitamin và khoáng chất cho bạn cảm giác sảng khoái, đầy sức khoẻ. Sản phẩm cung cấp năng lượng cho cơ thể và giúp giải khát nhanh chóng',
9500, 14
),
(
'Nước Ngọt Fanta Cam 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-fanta-cam-320ml-1654161193?w=960&type=s',
'Là sản phẩm nước ngọt có gas của thương hiệu Fanta nổi tiếng giúp giải khát sau khi hoạt động ngoài trời, giải tỏa căng thẳng, mệt mỏi khi học tập, làm việc. Nước ngọt Fanta hương cam lon 330ml thơm ngon kích thích vị giác, chứa nhiều vitamin C sẽ cung cấp năng lượng cho cơ thể khỏe mạnh',
10000, 14
),
(
'Nước Ngọt Pepsi Zero 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-pepsi-zero-320ml-1654681443?w=960&type=s',
'Là loại nước ngọt được nhiều người yêu thích đến từ thương hiệu nổi tiếng thế giới Pepsi với hương vị thơm ngon, sảng khoái. Nước ngọt Pepsi không calo lon 320ml với lượng gas lớn sẽ giúp bạn xua tan mọi cảm giác mệt mỏi, căng thẳng, sản phẩm không calo lành mạnh, tốt cho sức khỏe',
12000, 14
),
(
'Nước Ngọt Pepsi Vị Chanh Không Calo 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-pepsi-vi-chanh-khong-calo-320ml-1654681433?w=960&type=s',
'Sự kết hợp hài hòa của vị chanh thanh mát, giải nhiệt và mang lại cảm giác sảng khoái dài lâu và tốt cho sức khỏe. Nước ngọt Pepsi không calo vị chanh lon 320ml cực kỳ thích hợp cho những người thích uống nước ngọt nhưng vẫn muốn giữ lối sống ăn thanh đạm, ít đường. Sản phẩm chất lượng từ nhà Pepsi',
12000, 14
),
(
'Ginger Ale Schweppes 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-ginger-ale-schweppes-320ml-1653710901?w=960&type=s',
'Nước ngọt Schweppes sử dụng những hương liệu chiết xuất từ thiên nhiên, công thức riêng biệt kết hợp gừng cay nhẹ, nước ngọt có ga với vị mạnh mẽ mang tới cảm giác sảng khoái. Nước Soda Schweppes Dry Ginger Ale lon 320ml có thể dùng ngay hoặc dùng để pha chế các loại đồ uống hấp dẫn',
10500, 14
),
(
'Lốc 6 Nước Ngọt Coca Cola Chai Pet 390ml - 1 Lốc',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-loc-6-nuoc-ngot-coca-cola-chai-pet-390ml-1656950661?w=960&type=s',
'Từ thương hiệu nước giải khát hàng đầu thế giới, lốc 6 Nước Ngọt Coca Cola chai pet 390ml xua tan nhanh mọi cảm giác mệt mỏi, căng thẳng, đặc biệt thích hợp sử dụng với các hoạt động ngoài trời. Bên cạnh đó thiết kế dạng chai nhỏ gọn, tiện lợi dễ dàng bảo quản khi không sử dụng hết
Thương hiệu: Coca Cola (Mỹ)
Lượng ga: Có ga
Lượng đường: Có đường
Thể tích: 390ml
Số lượng: 6 chai
Sử dụng: Ngon hơn khi uống lạnh',
55000, 14
),
(
'Nước Ngọt 7up 1.5 Lít - 1 Chai',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-7up-1-5-lit-1653555491?w=960&type=s',
'Từ thương hiệu nước giải khát 7Up uy tín được nhiều người ưa chuộng. Nước Ngọt 7up 1.5 Lít có vị ngọt vừa phải và hương vị gas the mát, giúp bạn xua tan nhanh chóng cơn khát, giảm cảm giác ngấy, kích thích vị giác giúp ăn ngon hơn, cung cấp năng lượng cho tinh thần tươi vui mỗi ngày',
21000, 14
),
(
'Nước Ngọt 7up 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-7up-320ml-1653555440?w=960&type=s',
'Từ thương hiệu nước giải khát 7Up uy tín được nhiều người ưa chuộng. Nước Ngọt 7up 320ml có vị ngọt vừa phải và hương vị gas the mát, giúp bạn xua tan nhanh chóng cơn khát, giảm cảm giác ngấy, kích thích vị giác giúp ăn ngon hơn, cung cấp năng lượng cho tinh thần tươi vui mỗi ngày',
12000, 14
),
(
'Nước Ngọt Fanta Cam Chai 600ml - 1 Chai',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-fanta-cam-chai-600ml-1654185103?w=960&type=s',
'Là sản phẩm nước ngọt có gas của thương hiệu Fanta nổi tiếng giúp giải khát sau khi hoạt động ngoài trời, giải tỏa căng thẳng, mệt mỏi khi học tập, làm việc. Nước Ngọt Fanta Cam Chai 600ml thơm ngon kích thích vị giác, chứa nhiều vitamin C sẽ cung cấp năng lượng cho cơ thể khỏe mạnh',
10000, 14
),
(
'Nước Ngọt Fanta Green Cream 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-fanta-green-cream-320ml-1654161226?w=960&type=s',
'Mirinda soda kem không chỉ giúp bạn xua tan cơn khát tức thì mà còn giúp kích thích vị giác, cho bữa ăn thêm ngon miệng. Đây là thức uống không thể thiếu trong các buổi tiệc, hoạt động ngoài trời, giúp bạn tràn đầy năng lượng, căng tràn sức sống hoạt động dưới thời tiết mùa hè đầy nắng nóng',
10000, 14
),
(
'Nước Ngọt Fanta Vị Xá Xị 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-fanta-vi-xa-xi-320ml-1655532727?w=960&type=s',
'Là sản phẩm nước ngọt có gas của thương hiệu Fanta nổi tiếng giúp giải khát sau khi hoạt động ngoài trời, giải tỏa căng thẳng, mệt mỏi khi học tập, làm việc. Nước ngọt Fanta vị xá xị 320ml thơm ngon kích thích vị giác, cung cấp năng lượng cho cơ thể. Cam kết chính hãng, an toàn
Thương hiệu: Fanta (Việt Nam)',
9500, 14
),
(
'Nước Ngọt Mirinda Cam 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-mirinda-cam-320ml-1654161048?w=960&type=s',
'Sản phẩm nước ngọt giải khát từ thương hiệu Mirinda nổi tiếng được nhiều người ưa chuộng. Nước ngọt Mirinda cam 320ml với hương vị cam đặc trưng, không chỉ giải khát, mà còn bổ sung thêm vitamin C giúp lấy lại năng lượng cho hoạt động hàng ngày. Cam kết chính hãng và an toàn
Hướng dẫn sử dụng: Dùng trực tiếp, ngon hơn khi uống lạnh.
Bảo quản: Để nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp.',
11000, 14
),
(
'Nước Ngọt Mirinda Cam 390ml - 1 Chai',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-mirinda-cam-390ml-1653556690?w=960&type=s',
'Sản phẩm nước ngọt giải khát từ thương hiệu Mirinda nổi tiếng được nhiều người ưa chuộng. Nước ngọt Mirinda cam 390ml với hương vị cam đặc trưng, không chỉ giải khát, mà còn bổ sung thêm vitamin C giúp lấy lại năng lượng cho hoạt động hàng ngày. Cam kết chính hãng và an toàn
Hướng dẫn sử dụng: Dùng trực tiếp, ngon hơn khi uống lạnh.
Bảo quản: Để nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp.',
8000, 14
),
(
'Nước Ngọt Mirinda Soda Kem 1.5 Lít - 1 Chai',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-mirinda-soda-kem-1-5-lit-1653556905?w=960&type=s',
'Sản phẩm nước ngọt giải khát từ thương hiệu Mirinda nổi tiếng được nhiều người ưa chuộng với hương vị độc đáo hấp dẫn. Nước ngọt Mirinda vị Soda kem 1.5 lít có vị ngọt dịu, không chỉ giúp xua tan cơn khát tức thì mà còn giúp kích thích vị giác, cho bữa ăn thêm ngon miệng',
22000, 14
),
(
'Nước Ngọt Mirinda Soda Kem 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-mirinda-soda-kem-320ml-1653557074?w=960&type=s',
'Sản phẩm nước ngọt giải khát từ thương hiệu Mirinda nổi tiếng được nhiều người ưa chuộng với hương vị độc đáo hấp dẫn. Nước ngọt Mirinda Soda kem 320ml có vị ngọt dịu, không chỉ giúp xua tan cơn khát tức thì mà còn giúp kích thích vị giác, cho bữa ăn thêm ngon miệng',
11000, 14
),
(
'Nước Ngọt Mirinda Soda Kem Việt Quất 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-mirinda-soda-kem-viet-quat-320ml-1653384026?w=960&type=s',
'Thương hiệu: Mirinda (Việt Nam)
Sản xuất: tại Việt Nam
Loại nước nước ngọt có ga
Hương vị soda kem việt quất
Lượng ga: Có ga
Lượng đường: Có đường
Thể tích 320ml
Sử dụng Ngon hơn khi uống lạnh
Điểm nổi bật Nước uống có ga vị soda kem việt quất tươi mới, thơm ngon bất ngờ, giải khát tức thì.',
10500, 14
),
(
'Nước Ngọt Pepsi 390ml - 1 Chai',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-pepsi-390ml-1654681455?w=960&type=s',
'Từ thương hiệu nước ngọt có gas nổi tiếng toàn cầu với mùi vị thơm ngon với hỗn hợp hương tự nhiên cùng chất tạo ngọt tổng hợp, giúp xua tan cơn khát và cảm giác mệt mỏi.  Nước ngọt bổ sung năng lượng làm việc mỗi ngày. Cam kết sản phẩm chính hãng, chất lượng và an toàn
 Ngon hơn khi uống lạnh',
9000, 14
),
(
'Nước Ngọt Sprite Chanh 235ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-sprite-chanh-235ml-1654161241?w=960&type=s',
'Hương vị được ưa chuộng tại hơn 190 quôc gia và lọt top những nước giải khát được yêu thích nhất toàn cầu. Với vị chanh tươi mát cùng những bọt ga sảng khoái tê đầu lưỡi giúp bạn đập tan cơn khát ngay tức thì. Sản phẩm cam kết chính hãng, chất lượng và an toàn từ nhà Sprite',
9000, 14
),
(
'Nước Ngọt Twister Cam Lon 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-nuoc-ngot-twister-cam-lon-320ml-1653711965?w=960&type=s',
'Được chiết xuất từ những tép cam tươi nguyên chất tươi ngon và bổ dưỡng. Nước cam ép Twister với nguồn nguyên liệu tự nhiên được lựa chọn cẩn thận kết hợp công nghệ sản xuất hiện đại, mang lại thức uống có hương vị thơm ngon, tốt cho sức khỏe. Cam kết chính hãng và an toàn',
12000, 14
),
(
'Sá Xị Chương Dương 330ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-sa-xi-chuong-duong-330ml-1654161752?w=960&type=s',
'Đây Là sản phẩm truyền thống đặc trưng của Chương Dương mang hương vị độc đáo rất được ưa chuộng. Với hương vị sá xị nồng nàn đi kèm với các thành phần quế, hồi hỗ trợ tốt cho hệ tiêu hóa, tuần hoàn, làm ấm cơ thể...cung cấp năng lượng và hàm lượng khoáng chất dồi dào xua đi cơn khát, căng thẳng mệt mỏi
Hướng dẫn sử dụng: Lắc nhẹ trước khi uống, dùng ngay sau khi mở nắp. Ngon hơn khi uống lạnh
Bảo quản Để nơi khô ráo, thoáng mát, tránh ánh sáng trực tiếp hoặc nơi có nhiệt độ cao.
Thương hiệu: Chương Dương (Sản Xuất tại Việt Nam)
Dung tích: 330ml',
11000, 14
),
(
'Sá Xị Chương Dương Zero 330ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-sa-xi-chuong-duong-zero-330ml-1653712534?w=960&type=s',
'Nước giải khát có gaz Sá xị Chương Dương, với hương vị đặc trưng của Sá xị không hề thay đổi từ năm 1952 sẽ là sự lựa chọn đúng đắn giúp đánh bay cơn khát của bạn. Ngoài mục đích giải khát, Sá xị Chương Dương còn mang lại nhiều lợi ích cho sức khỏe nhờ vào các thành phần tự nhiên của mình',
10500, 14
),
(
'Soda Schweppes 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-soda-schweppes-320ml-1653712410?w=960&type=s',
'Sản xuất theo dây chuyền hiện đại kiểm định nghiêm ngặt. 24 lon nước Soda Schweppes 330ml là thức uống giải khát giúp bổ sung vitamin và khoáng chất tốt cho cơ thể, giúp hanh chóng để bù nước cho cơ thể khi vận động nhiều hoặc chơi thể thao. Cam kết chất lượng an toàn từ thương hiệu Schweppes',
8000, 14
),
(
'Thùng Nước Ngọt Coca Cola 320ml 24 Lon - 1 Thùng',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-thung-nuoc-ngot-coca-cola-320ml-24-lon-1657211698?w=960&type=s',
'Là loại nước ngọt được nhiều người yêu thích với hương vị thơm ngon, sảng khoái. Thùng Nước ngọt Coca Cola 320ml 24 lon với lượng gas lớn sẽ giúp bạn xua tan mọi cảm giác mệt mỏi, căng thẳng, đem lại cảm giác thoải mái sau khi hoạt động ngoài trời.',
235000, 14
),
(
'Tonic Schweppes 320ml - 1 Lon',
'https://tea-3.lozi.vn/v1/ship/resized/product-avatar-tonic-schweppes-320ml-1653712285?w=960&type=s',
'Loại thức uống pha sẵn nổi tiếng của thương hiệu Schweppes, Soda Water Schweppes Tonic được sản xuất trên dây chuyền công nghệ hiện đại, đảm bảo chất lượng vệ sinh an toàn thực phẩm cho người dùng.
Với loại soda này, bạn có thể kết hợp với các loại rượu để chế biến thành món đồ uống thơm ngon, lạ miệng.
Tonic không chỉ lạ miệng mà còn rất giàu chất khoáng, Vitamin, rất tốt cho sức khỏe.
Sản phẩm được đóng lon tiện lợi, dễ sử dụng và bảo quản.',
8500, 14
);

insert into warehouse (quantity, product_id) VALUES (100,1);
insert into warehouse (quantity, product_id) VALUES (100,2);
insert into warehouse (quantity, product_id) VALUES (100,3);
insert into warehouse (quantity, product_id) VALUES (100,4);
insert into warehouse (quantity, product_id) VALUES (100,5);
insert into warehouse (quantity, product_id) VALUES (100,6);
insert into warehouse (quantity, product_id) VALUES (100,7);
insert into warehouse (quantity, product_id) VALUES (100,8);
insert into warehouse (quantity, product_id) VALUES (100,9);
insert into warehouse (quantity, product_id) VALUES (100,10);
insert into warehouse (quantity, product_id) VALUES (100,11);
insert into warehouse (quantity, product_id) VALUES (100,12);
insert into warehouse (quantity, product_id) VALUES (100,13);
insert into warehouse (quantity, product_id) VALUES (100,14);
insert into warehouse (quantity, product_id) VALUES (100,15);
insert into warehouse (quantity, product_id) VALUES (100,16);
insert into warehouse (quantity, product_id) VALUES (100,17);
insert into warehouse (quantity, product_id) VALUES (100,18);
insert into warehouse (quantity, product_id) VALUES (100,19);
insert into warehouse (quantity, product_id) VALUES (100,20);
insert into warehouse (quantity, product_id) VALUES (100,21);
insert into warehouse (quantity, product_id) VALUES (100,22);
insert into warehouse (quantity, product_id) VALUES (100,23);
insert into warehouse (quantity, product_id) VALUES (100,24);
insert into warehouse (quantity, product_id) VALUES (100,25);
insert into warehouse (quantity, product_id) VALUES (100,26);
insert into warehouse (quantity, product_id) VALUES (100,27);
insert into warehouse (quantity, product_id) VALUES (100,28);
insert into warehouse (quantity, product_id) VALUES (100,29);
insert into warehouse (quantity, product_id) VALUES (100,30);
insert into warehouse (quantity, product_id) VALUES (100,31);
insert into warehouse (quantity, product_id) VALUES (100,32);


