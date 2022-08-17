<?php
$Err_1001 = "Đã xảy ra lỗi, vui lòng thử lại!";
$Err_1002 = "Email chưa được đăng ký, vui lòng thử lại!";
$Err_1003 = "Email đã được đăng ký!";
$Err_1004 = "Mật khẩu chưa chính xác!";
$Err_1005 = "Tài khoản đã bị khóa, vui lòng liên hệ admin để mở khóa!";
$Err_1013 = "Vui lòng nhập đủ dữ liệu!";
$Err_1020 = "Tài khoản không đủ quyền";

function getErrorMessage($code)
{
    $errMessage = "";
    switch ($code) {
        case 1001:
            $errMessage = "Đã xảy ra lỗi, vui lòng thử lại!";
            break;
        case 1002:
            $errMessage = "Email chưa được đăng ký, vui lòng thử lại!";
            break;
        case 1003:
            $errMessage = "Email đã được đăng ký!";
            break;
        case 1004:
            $errMessage = "Mật khẩu chưa chính xác!";
            break;
        case 1005:
            $errMessage = "Tài khoản đã bị khóa, vui lòng liên hệ admin để mở khóa!";
            break;
        case 1011:
            $errMessage = "Đã tồn tại, vui lòng chọn tên khác!";
            break;
        case 1013:
            $errMessage = "Vui lòng nhập đủ dữ liệu!";
            break;
        case 1014:
            $errMessage = "Không tìm thấy!";
            break;
        case 1020:
            $errMessage = "Tài khoản không đủ quyền";
            break;
        case 1021:
            $errMessage = "Không tìm thấy đơn hàng";
            break;
        case 1022:
            $errMessage = "Không thể chọn chính bản thân làm thể loại";
            break;
    }
    return $errMessage;
}

if (isset($_POST['code'])) {
    echo getErrorMessage($_POST['code']);
}
