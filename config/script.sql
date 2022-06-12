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
`branch_id` INT(11) NOT NULL,
FOREIGN KEY (`product_id`) REFERENCES products(`id`),
FOREIGN KEY (`branch_id`) REFERENCES branches(`id`)
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
( 'Đồng hồ', 'https://cdn.pixabay.com/photo/2014/07/31/23/00/wristwatch-407096__340.jpg'),
( 'LapTop', 'https://cdn.pixabay.com/photo/2016/04/04/14/12/monitor-1307227__340.jpg'),
( 'Điện thoại', 'https://cdn.tgdd.vn/Products/Images/42/230529/iphone-13-pro-max-1-2.jpg'),
( 'Console', 'https://product.hstatic.net/1000026716/product/gearvn.com-products-tay-cam-ps5-dualsense-1_6a7c1e831fc94f48aac6e0a5a15da140.jpg'),
( 'Thiết bị mạng', 'https://www.totolink.vn/public/uploads/img_article/6thietbimangcobanbancanbietrouterlagi.png'),
( 'Chuột Gaming', 'https://product.hstatic.net/1000026716/product/da_e_01_8347be5a11f14bf48e1a472ae080a1c1_be824480da3f4c30b4b15a68f0fd058b.jpg'),
( 'Bàn phím Gaming', 'https://product.hstatic.net/1000026716/product/logitechg613-gearvn.jpg'),
( 'Tai nghe Gaming', 'https://product.hstatic.net/1000026716/product/hyperx-cloud-stinger-core-71-5_00502a3b6c134f30ac98e0542e29a19b_grande_2f970ccc0fc44c239e117501d9ffa0d1.jpg'),
( 'Màn hình', 'https://product.hstatic.net/1000026716/product/aoc_24g2e_gearvn_1011dc9ce7b8450d993df4516006aa7f.jpg');


insert into `branches` (`name`, `address`) values 
('CSC 1','350/56/21A Nguyễn Văn Lượng p16, Quận Gò Vấp'),
('CSC 2', '350/56/21A Nguyễn Văn Lượng p16, Quận Gò Vấp'),
('CSC 3', '350/56/21A Nguyễn Văn Lượng p16, Quận Gò Vấp'),
('CSC 4', '350/56/21A Nguyễn Văn Lượng p16, Quận Gò Vấp');


insert into `products` ( `name`, `avatar`,`description`,`price`,`category_id`) values 

( 'Apple watch S6','https://cdn.tgdd.vn/Products/Images/7077/229044/apple-watch-s6-40mm-vien-nhom-day-cao-su-do-thumb-600x600.jpg',
'Apple Watch S6 mang đến những nâng cấp hữu ích để hỗ trợ người dùng một cách tối ưu nhất. Nổi bật trong số đó là chip xử lý S6 cải thiện hiệu năng, 
hệ điều hành watchOS 7 với nhiều tính năng mới hứa hẹn sẽ mang đến những trải nghiệm tốt hơn, thú vị hơn',3000000, 1),

( 'Samsung Galaxy Watch 3','https://cdn.tgdd.vn/Products/Images/7077/226475/samsung-galaxy-watch-3-41mm-vang-dong-thumb-1-1-600x600.jpg',
'Đồng hồ thông minh Samsung Galaxy Watch 3 41 mm sở hữu 2 nút bấm và vòng bezel xoay điều khiển vật lý, thay cho mặt xoay cảm ứng ở phiên bản Watch Active 2.
Màn hình Super Amoled 1.2 inch cùng độ phân giải 360 x 360 pixels giúp hình ảnh hiển thị được chân thật, rõ nét. Thân đồng hồ được làm bằng thép cứng cáp, 
khác với Watch Active 2 được làm bằng nhôm. Dây đeo bằng da tạo cảm giác chắc chắn và dễ chịu cho người dùng khi đeo.',5500000, 1),

( 'Samsung Galaxy Watch 4','https://cdn.tgdd.vn/Products/Images/7077/248753/samsung-galaxy-watch-4-44mm-thumb-03-600x600.jpg',
'Samsung Galaxy S4 mang đến những nâng cấp hữu ích để hỗ trợ người dùng một cách tối ưu nhất. Nổi bật trong số đó là chip xử lý mới cải thiện hiệu năng, 
hệ điều hành mới với nhiều tính năng mới hứa hẹn sẽ mang đến những trải nghiệm tốt hơn, thú vị hơn',7345000, 1),

( 'Gramin Lily day silicone','https://cdn.tgdd.vn/Products/Images/7077/235503/garmin-lily-day-silicone-trang-thumb-1-1-600x600.jpg',
'Chiếc đồng hồ có thiết kế nhỏ gọn với đường kính mặt chỉ 35 mm và trọng lượng nhẹ 24 g. Chất liệu khung viền được làm từ polyme bền chắc chịu sự va chạm tốt. 
Bên cạnh đó, dây đeo silicone mềm dẻo, êm ái và ôm gọn cổ tay có độ rộng dây thanh mảnh 1.4 cm, độ dài dây 10 cm. Đây là những ưu điểm thiết kế mà 
chiếc đồng hồ thông minh Lily sở hữu.',830780, 1),

( 'Laptop Gaming MSI Katana GF66 11UC 676VN','https://product.hstatic.net/1000026716/product/product_1619086146fdfbc8b34331ebecbf18cb444480b7d1_ac2062f64fe84910a66f33a40a7f03b2.png',
'GeForce RTX™ 30 Series GPU mang đến sức mạnh tối thượng cho game thủ và người sáng tạo nội dung. Sử dụng kiến trúc Ampere danh giá đã đạt nhiều giải thưởng
uy tín —cũng là kiến trúc RTX thế hệ thứ 2 của NVIDIA —với nhân RT và nhân Tensor mới, cùng với đa nhân xử lí streaming giúp đem lại đồ họa ray-tracing siêu 
chân thực và các tính năng AI tân tiến nhất.',23000000, 2),

( 'Laptop ASUS TUF Gaming F15 FX506LH HN188W','https://product.hstatic.net/1000026716/product/fx506lh-hn188w_cd7936a34e974a3ba9e07503e4fcda53.jpg',
'Laptop gaming ASUS TUF Gaming F15 FX506LH HN188W được trang bị với CPU Intel Core i5-10300H và GPU GeForce GTX™ 1650 mạnh mẽ, các tựa game hành động sẽ chạy nhanh, mượt mà và khai thác tối 
đa màn hình IPS tần số quét 144Hz.Hệ thống tản nhiệt tự làm sạch hiệu quả kết hợp với độ bền đạt chuẩn quân đội của TUF giúp chiếc máy trở thành chiến binh bền bỉ đáng tin cậy cho các game thủ.'
,19500000, 2),

('Laptop Gaming Acer Nitro 5 Eagle AN515 57 5669','https://product.hstatic.net/1000026716/product/laptop_gaming_acer_nitro_5_eagle_an515_57_5669_a1581d79b72e45239cf8ffaad2c866f4.jpg',
'Laptop gaming ASUS TUF Gaming F15 FX506LH HN188W được trang bị với CPU Intel Core i5-10300H và GPU GeForce GTX™ 1650 mạnh mẽ, các tựa game hành động sẽ chạy nhanh, mượt mà và khai thác tối 
đa màn hình IPS tần số quét 144Hz.Hệ thống tản nhiệt tự làm sạch hiệu quả kết hợp với độ bền đạt chuẩn quân đội của TUF giúp chiếc máy trở thành chiến binh bền bỉ đáng tin cậy cho các game thủ.'
,21340000, 2),

( 'Laptop Gaming MSI Katana GF66 11UC 676VN','https://product.hstatic.net/1000026716/product/product_1619086146fdfbc8b34331ebecbf18cb444480b7d1_ac2062f64fe84910a66f33a40a7f03b2.png',
'GeForce RTX™ 30 Series GPU mang đến sức mạnh tối thượng cho game thủ và người sáng tạo nội dung. Sử dụng kiến trúc Ampere danh giá đã đạt nhiều giải thưởng
uy tín —cũng là kiến trúc RTX thế hệ thứ 2 của NVIDIA —với nhân RT và nhân Tensor mới, cùng với đa nhân xử lí streaming giúp đem lại đồ họa ray-tracing siêu 
chân thực và các tính năng AI tân tiến nhất.',18000000,  2),

( 'Iphone 13 Pro Max 128GB','https://cdn.tgdd.vn/Products/Images/42/230529/iphone-13-pro-max-gold-1-600x600.jpg',
'iPhone 13 Pro Max 128 GB - siêu phẩm được mong chờ nhất ở nửa cuối năm 2021 đến từ Apple. Máy có thiết kế không mấy đột phá khi so với người tiền nhiệm, 
bên trong đây vẫn là một sản phẩm có màn hình siêu đẹp, tần số quét được nâng cấp lên 120 Hz mượt mà, cảm biến camera có kích thước lớn hơn, cùng hiệu năng
mạnh mẽ với sức mạnh đến từ Apple A15 Bionic, sẵn sàng cùng bạn chinh phục mọi thử thách.',33000000, 3),

( 'OPPO Reno6 Z 5G','https://cdn.tgdd.vn/Products/Images/42/239747/oppo-reno6-z-5g-aurora-1-600x600.jpg',
'Reno6 Z 5G đến từ nhà OPPO với hàng loạt sự nâng cấp và cải tiến không chỉ ngoại hình bên ngoài mà còn sức mạnh bên trong. Đặc biệt, chiếc điện thoại 
được hãng đánh giá “chuyên gia chân dung bắt trọn mọi cảm xúc chân thật nhất”, đây chắc chắn sẽ là một “siêu phẩm" mà bạn không thể bỏ qua.',11000000, 3),

( 'Xiaomi 11T 5G 256GB','https://cdn.tgdd.vn/Products/Images/42/251216/Xiaomi-11T-Grey-600x600.jpg',
'Xiaomi 11T 5G sở hữu màn hình AMOLED, viên pin siêu khủng cùng camera độ phân giải 108 MP, chiếc smartphone này của Xiaomi sẽ đáp ứng mọi nhu cầu sử dụng của bạn, 
từ giải trí đến làm việc đều vô cùng mượt mà. ',9200000, 3),

( 'Vivo Y21s 6GB','https://cdn.tgdd.vn/Products/Images/42/250755/vivo-y21s-white-600x600.jpg',
'Vivo chính thức tung ra chiếc điện thoại Vivo Y21s với hàng loạt các ưu điểm nổi bật, không chỉ ngoại hình bên ngoài mà cả sức mạnh bên trong. Đặc biệt, chiếc 
smartphone này còn sở hữu mức giá cực tốt trong phân khúc, hứa hẹn sẽ đáp ứng tốt nhu cầu giải trí của bạn.',6000000, 3),

( 'Tay cầm PS5 DualSense','https://product.hstatic.net/1000026716/product/gearvn.com-products-tay-cam-ps5-dualsense-1_6a7c1e831fc94f48aac6e0a5a15da140_large.jpg',
'Tay cầm PS5 DualSense là một trong những tay cầm chơi game đa kết nối không chỉ kết nối ổn định với các dòng máy chơi game, mà đây còn là một trong những thiết bị hỗ trợ 
game thủ mobile, tay cầm chơi game pc,...',1900000, 4),

( 'Máy chơi game Sony PlayStation Classic - Limited' , 'https://product.hstatic.net/1000026716/product/untitled-1_6249db790c6548be9e10ea90aaf42298_large.jpg',
'Vivo chính thức tung ra chiếc điện thoại Vivo Y21s với hàng loạt các ưu điểm nổi bật, không chỉ ngoại hình bên ngoài mà cả sức mạnh bên trong. Đặc biệt, chiếc 
smartphone này còn sở hữu mức giá cực tốt trong phân khúc, hứa hẹn sẽ đáp ứng tốt nhu cầu giải trí của bạn.',2990000, 4),

( 'Máy chơi game Sony Playstation 4 Slim 1TB MegaPack 3','https://product.hstatic.net/1000026716/product/asia-00390_mega-pack_3_bundle-sleeve_3d_sea_wo_plus_1e612c223fa747ac8b81500f53e8ed21_large.png',
'Máy chơi game Sony Playstation 4 Slim có kích thước nhỏ gọn hơn nhiều so với phiên bản ban đầu, ít chiếm diện tích hơn và đem lại sự tinh tế cho căn phòng của bạn. Lớp vỏ được phủ nhựa matte màu đen nhám 
chống bám vân tay, giúp thiết bị luôn mới khi sử dụng thời gian dài.',8900000, 4),

( 'Máy chơi game Sony Playstation 4 Pro 1TB - OM 2','https://product.hstatic.net/1000026716/product/1_01530a817e0c4d92b8b38e08a8ca8f12_large.png',
'Được thiết kế để cải thiện nền đồ họa cũ và mới của PS4, Pro sẽ là một phiên bản máy chơi game đặc biệt, có thể nói là đặc biệt nhất trong lịch sử game thế giới. 
Mẫu PS4 mới mang tên gọi PS4 Pro này sẽ hướng đến những game thủ có nhu cầu chơi game trên độ phân giải 4K.',11900000, 4),

( 'Bộ định tuyến WiFi 5 TP-Link Archer C54 chuẩn AC1200','https://product.hstatic.net/1000026716/product/gearvn-thiet-bi-mang-tp-link-archer-c54-666_9c75da36356e41df8507b31a09741fc2.jpg',
'Với 4 ăng ten ngoài hiệu suất cao, thiết bị mạng TP-Link Archer C54 cung cấp vùng phủ xuyên suốt ngôi nhà của bạn. Công nghệ Beamforming giúp phát hiện các thiết bị kết nối và tập trung tín 
hiệu Wi-Fi về hướng thiết bị, ngay cả khi các thiết bị kết nối ở xa hay có công suất thấp.',490000, 5),

( 'Bộ định tuyến WiFi 5 TP-Link Archer C24 chuẩn AC750','https://product.hstatic.net/1000026716/product/archer-c24_un_1.0_01_normal_1594970251730j_abc29c3d0609464590c2ebacafe62393.jpg',
'Băng tần kép 2.4GHz / 5GHz
Bộ định tuyến TP-Link Archer C24 được trang bị 2 băng tần mạnh mẽ: 2.4GHz cho khả năng lướt web mượt mà, đọc mail, xem hình ảnh; 5GHz đem lại tốc độ 433Mbps cho kết nối nhanh chóng, chơi game 
và xem phim 4K thoải mái trên cả điện thoại và PC.',459000, 5),

( 'Bộ định tuyến WiFi 5 Asus GT-AC5300 chuẩn AC5300 (chuyên gaming)','https://product.hstatic.net/1000026716/product/ture_gt-ac5300__gaming_wifi_router__ac5300_wtfast_3_bang_tan_gearvn_00.jpg',
'Router WiFi Gaming ba băng tần GT-AC5300 - Giải pháp tốt nhất cho chơi game thực tế ảo VR và truyền phát 4K với bộ vi xử lý lõi tứ cổng gaming, WTFast, Adaptive QoS và bảo vệ mạng AiProtection.'
,4900000, 5),

( 'Bộ định tuyến WiFi 5 ASUS RT-AC1500UHP Chuẩn AC1500 (Xuyên tường)','https://product.hstatic.net/1000026716/product/1-rt-ac1500uhp-photo_79aebffb6e074a1190919bb4c550c9dd.png',
'Được tăng cường bằng Wi-Fi thế hệ thứ 5 (Wi-Fi 5G), chipset 802.11ac giúp RT-AC1500UHP đạt tốc độ không dây siêu nhanh. RT-AC1500UHP cho tốc độ lên tới 867 Mbps ở băng tần 5Ghz và 4x4 
600 Mbps ở băng tần 2,4 Ghz, nhanh hơn 2 lần so với các bộ định tuyến 300 Mbps. Với UI (giao diện) bảng điều khiển ASUSWRT, có thể cài đặt, theo dõi và điều khiển trực quan mọi ứng dụng 
mạng tại một nơi. Chỉ với 30 giây cài đặt, chức năng phát hiện đa thiết bị và các thiết lập linh hoạt, ASUSWRT sẽ khai thác tối đa hiệu suất mạng của bạn.',1900000, 5),

( 'Chuột Logitech G Pro X Superlight Wireless White','https://product.hstatic.net/1000026716/product/gearvn-chuot-logitech-g-pro-x-superlight-wireless-white-666_1b449789ba424d6bb38370ca7bdecf2a_large.jpg',
'Chuột Logitech G Pro X Superlight Wireless White là một trong những dòng chuột chơi game nhẹ nhất từ trước tới nay của Logitech, Logitech G Pro X Superlight Wireless White là bước đột phá về kỹ thuật khi đạt 
được trọng lượng ít hơn 63 gram nhẹ hơn gần 25% so với chuột PRO không dây tiêu chuẩn của Logitech.',2900000, 6),

( 'Chuột Logitech G Pro X Superlight Wireless Black','https://product.hstatic.net/1000026716/product/gearvn-chuot-logitech-g-pro-x-superlight-wireless-black-666_83650815ce2e486f9108dbbb17c29159_large.jpg',
'Chuột Logitech G Pro X Superlight Wireless Black là một trong những dòng chuột chơi game nhẹ nhất từ trước tới nay của Logitech, Logitech G Pro X Superlight Wireless White là bước đột phá về kỹ thuật khi đạt 
được trọng lượng ít hơn 63 gram nhẹ hơn gần 25% so với chuột PRO không dây tiêu chuẩn của Logitech.',2900000, 6),

( 'Chuột Razer Deathadder Essential White','https://product.hstatic.net/1000026716/product/screenshot_2021-06-22_084125_bc900411125c4473a0bf6a3bc6b58f1b.png',
'Chuột gaming DeathAdder Essential White được Razer thiết kế với kiểu dáng công thái học (Ergonomic) cổ điển. Thiết kế đẹp mắt và khác biệt ở các dòng chuột gaming khác tạo ra sự thoải mái, cho phép người chơi 
duy trì mức hiệu suất cao trong suốt thời gian chơi game dài, vì vậy bạn sẽ không bao giờ bị ngập ngừng trong các trận chiến nóng bỏng.',590000, 6),

( 'Chuột Steelseries Rival 650','https://product.hstatic.net/1000026716/product/rival650-gearvn_large.jpg',
'Rival 650 của Steelseries là một trong những dòng sản phẩm chuột không dây được trang bị nhiều công nghệ tối tân nhất thế giới. Chuột được thiết kế công thái học hoàn hảo cho người thuận 
tay phải, tối ưu cho kiểu cầm palm-grip và claw-grip.',2590000, 6),

( 'Bàn phím Logitech G610 Orion','https://hstatic.net/716/1000026716/1/2016/8-22/untitled-1_3340603f-26bd-4022-509d-3522cbb677cf.png',
'Logitech G610 Orion là một trong những dòng bàn phím cơ được thiết kế dành riêng cho game thủ với thiết kế hơi hướng classic, ít phá cách nhưng vẫn sang trọng và cá tính. Bề mặt sản phẩm và các nút bấm sử
dụng chất liệu nhựa cao cấp, chắc chắn và không để lại vân tay.',1740000, 7),

( 'BBàn phím Leopold FC650MDS Bluetooth Blue Grey','https://product.hstatic.net/1000026716/product/_day_leopold_fc650m_ds_bt_red_silent_sw_usb_bluetooth_blue_grey_0000_1_360a15463641499995c40e1686e9a840.jpg',
'Kết nối không dây cực kỳ ổn định, khi Leopold FC650MDS được trang bị kết nối Bluetooth 5.1. Theo đó, layout được thiết kế vô cùng độc đáo, nhỏ gọn. Khi các phần phím điều hướng được dời xuống phía dưới, và phần kê 
tay kèm theo mang lại cảm giác thoải mái khi sử dụng.',2740000, 7),

( 'Bàn phím Corsair K63','https://product.hstatic.net/1000026716/product/k63.01_large.png',
'Xét về ngoại hình, bàn phím Corsair K63 sở hữu thiết kế nhỏ gọn và tiện lợi như chính tên gọi của sản phẩm.Ngoài ra, mẫu bàn phím cơ của Corsair còn có các phím chức năng đa phương tiện, nút tùy chỉnh âm lượng, nút 
vô hiệu phím Windows và tắt tiếng độc lập rất thông minh và hữu ích khi sử dụng.',1940000, 7),

( 'Bàn phím Razer Blackwidow Lite','https://product.hstatic.net/1000026716/product/gearvn_razer_bwlite.png',
'Trung bình, bạn nhất định dành 1/3 cuộc đời của mình để làm việc. Đã đến lúc xem xét lại các công cụ bạn sử dụng hàng ngày — hãy gặp Razer BlackWidow Lite. Nó kết hợp khả năng phản hồi nhanh để chơi game 
với các tính năng giảm cân để trở nên tinh tế cho văn phòng. Các phím hiệu suất cao đáp ứng các bộ giảm âm o-ring và đèn nền LED màu trắng thực sự, giúp bạn tập trung và làm việc hiệu quả khi đánh máy ngay 
cả khi đã muộn.',2090000, 7),

( 'Tai nghe HyperX Could Stinger','https://product.hstatic.net/1000026716/product/hyperx-cloud-stinger-core-71-5_00502a3b6c134f30ac98e0542e29a19b_grande_2f970ccc0fc44c239e117501d9ffa0d1.jpg',
'HyperX Cloud Stinger Core™ là tai nghe gaming giá rẻ hoàn hảo cho game thủ điều khiển trò chơi thích chất lượng âm thanh hay nhưng giá mềm. Tương thích với nhiều loại máy và có bộ điều khiển âm thanh
ngay trên cáp. ',790000, 8),

( 'Tai nghe Logitech G733 LIGHTSPEED Wireless Lilac','https://product.hstatic.net/1000026716/product/71ffzv1rkzl._ac_sl1500_1_866730414efa4f1e9477e7bc6472bd2d.jpg',
'Tai nghe Logitech G733 LIGHTSPEED Wireless Lilac là một chiếc tai nghe không dây cực kỳ đáng mua. Được thiết kế để mang lại sự thoải mái cho người dùng, G733 LIGHTSPEED được trang bị âm thanh nổi, 
bộ lọc âm thanh và các chức năng ánh sáng tiên tiến nhất.',790000, 8),

( 'Tai nghe Razer Kraken X - Blue','https://product.hstatic.net/1000026716/product/gvn_krakenx_blue_503a882161bb495eaf6c0008478c1b68_large.png',
'HyperX Cloud Stinger Core™ là tai nghe gaming giá rẻ hoàn hảo cho game thủ điều khiển trò chơi thích chất lượng âm thanh hay nhưng giá mềm. Tương thích với nhiều loại máy và có bộ điều khiển âm thanh
ngay trên cáp. ',1290000, 8),

( 'Tai nghe Steelseries Arctis 7P+ Wireless White','https://product.hstatic.net/1000026716/product/gearvn-tai-nghe-steelseries-arctis-7p_-wireless-white-4_5d1cd168558f4d1b9716f1ff0762b0af.png',
'Nếu bạn đang tìm kiếm một chiếc tai nghe gaming không dây thì chắc hẳn không thể bỏ qua chiếc Steelseries Arctis 7P+ Wireless White đến từ nhà Steelseries này. Đi cùng với thiết kế độc đáo và những 
tính năng được cải thiện hơn so với các thế hệ trước.',4900000, 8),

( 'Màn hình Asus TUF GAMING VG249Q1A 24" IPS 165Hz Gsync compatible chuyên game','https://product.hstatic.net/1000026716/product/inh-asus-tuf-gaming-vg249q1a-24-ips-165hz-gsync-compatible-chuyen-game_b0fbfe425ebc40739fc163c018199f69.jpg',
'Màn hình Asus TUF GAMING VG249Q1A rộng 23,8 inch, sử dụng tấm nền IPS độ phân giải Full HD (1920x1080) với tốc độ làm mới 165Hz cực nhanh. TUF VG249Q1R được thiết kế dành cho các game thủ chuyên nghiệp và những người chơi muốn hòa mình vào các 
trò chơi thực sự. Không chỉ vậy, công nghệ ELMB độc quyền cho phép phản hồi trong vòng 1ms MPRT và sự kết hợp của công nghệ đồng bộ hóa thích ứng (FreeSync Premium) sẽ mang đến cho người dùng màn hình mượt mà và trải nghiệm chơi game 
tuyệt vời.',5290000, 9),

( 'Màn hình Asus TUF GAMING VG249Q1A 24" IPS 165Hz Gsync compatible chuyên game','https://product.hstatic.net/1000026716/product/inh-asus-tuf-gaming-vg249q1a-24-ips-165hz-gsync-compatible-chuyen-game_b0fbfe425ebc40739fc163c018199f69.jpg',
'Màn hình ViewSonic XG2405-2 24" IPS 144Hz Gsync compatible chuyên game sở hữu màn hình 24 inch. Nó là một trong những màn hình máy tính được trang bị tấm nền SuperClear IPS và tần số quét 144Hz, ViewSonic XG2405-2 được thiết kế riêng cho game 
thủ với sự pha trộn hoàn hảo của hiệu năng và màu sắc sống động.',5190000, 9),

( 'Màn hình GIGABYTE G24F 24" IPS 165Hz chuyên game" IPS 144Hz Gsync compatible chuyên game','https://product.hstatic.net/1000026716/product/gigabyte_g24f_gearvn_32c459bb9b714c35b32481e53b4d081e.jpg',
'Màn hình GIGABYTE G24F 24" IPS 165Hz chuyên game được thiết kế với kích thước 24 inch cùng phần viền mỏng giúp nâng cao cảm giác đắm chìm với khung hình mở rộng. Tấm nền IPS hiện đại mang đến góc nhìn rộng,
đồng thời cải thiện hiệu quả độ tương phản màn hình.',4290000, 9),

( 'Màn hình cong ViewSonic VX2719-PC-MHD 27" VA 240Hz chuyên game','https://product.hstatic.net/1000026716/product/viewsonic_vx2719-pc-mhd_gearvn_04563dd3de7d46beae77b21e7ee8d858.jpg',
'Với thời gian phản hồi MPRT 1ms nhanh. màn hình mang lại ảnh mượt mà, mà không bị vệt, mờ hoặc bóng mờ. Thời gian phản hồi nhanh phù hợp để chơi các tựa game cường độ cao nhất về đồ họa và cung cấp chất lượng hình ảnh tuyệt vời khi xem phim 
thể thao hoặc hành động',5290000, 9);

insert into warehouse (quantity, product_id, branch_id) VALUES (100,1,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,2,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,3,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,4,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,5,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,6,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,7,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,8,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,9,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,10,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,11,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,12,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,13,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,14,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,15,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,16,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,17,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,18,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,19,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,20,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,21,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,22,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,23,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,24,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,25,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,26,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,27,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,28,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,29,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,30,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,31,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,32,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,33,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,34,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,35,1);
insert into warehouse (quantity, product_id, branch_id) VALUES (100,36,1);

INSERT INTO `warehouse` (`id`, `quantity`, `created_at`, `product_id`, `branch_id`) VALUES 
(NULL, '100', current_timestamp(), '1', '1'), 
(NULL, '99', current_timestamp(), '2', '1'),
(NULL, '101', current_timestamp(), '3', '1');
