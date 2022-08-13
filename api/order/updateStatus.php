<?php
include_once '../../config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("POST");
$authen = new Authen();
$code = 1001;
$data = [];

if ($authen->checkToken()) {
    $order_code = $_POST["order_code"];
    $status = $_POST["status"];
    $data = (new OrderController())->updateStatus($order_code,$status);
    if ($data == 1000) {
        $code = 1000;
    } else {
        $code = $data;
    }
} else {
    $code = 401;
}

echo (
    (new Response(
        $code,
        null,
    ))->response()
);
