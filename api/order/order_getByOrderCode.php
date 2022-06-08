<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("GET");
$authen = new Authen();
$code = 1001;
$data = null;

if ($authen->checkToken()) {
    if (isset($_GET['order_code'])) {
        $order_code = $_GET["order_code"];
        $data = (new OrderController())->getByorder_code($order_code);
     
        if ($data == null) {
            $code = 1001;
        } else {
            $code = 1000;
        }
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
