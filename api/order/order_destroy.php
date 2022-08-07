<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("POST");
$authen = new Authen();
$code = 1001;
$data = null;

if ($authen->checkToken()) {
    if (isset($_POST["order_code"]) && isset($_POST["status"])) {
        $order_code = $_POST["order_code"];
        $status = $_POST["status"];
        if ($status != 0) {
            $code = 1014;
        } else {
            $data = (new OrderController())->destroyOrder($order_code, 4);
            if ($data == 1000) {
                $code = 1000;
            } else {
                $code = $data;
            }
            $data = null;
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
