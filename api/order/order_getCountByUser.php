<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("GET");
$authen = new Authen();

$code = 1001;
$data = [];


if ($authen->checkToken()) {
    if (isset($_GET['user_id'])) {
        $user_id = $_GET["user_id"];
        $data = (new OrderController())->getTotalOderCountByUser($user_id);
        $code = 1000;
        $data = array(
            "count" => $data
        );
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
