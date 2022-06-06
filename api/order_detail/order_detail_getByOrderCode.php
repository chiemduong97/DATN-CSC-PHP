<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/order_detail_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

$authen = new Authen();
(new CF_Header())->config("GET");
$code = 1001;
$data = [];

if ($authen->checkToken()) {
    if (isset($_GET["order_code"])) {
        $order_code = $_GET["order_code"];
        $data = (new OrderDetailController())->getByOrderCode($order_code);
        $data == 1001 ?  $code = 1001 :  $code = 1000;
    } else {
        $code = 1013;
    }
} else {
    $code = 401;
}

echo (
    (new Response(
        $code,
        $data,
    ))->response()
);
