<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("GET");

$code = 1001;
$data = [];
$authen = new Authen();

if ($authen->checkToken()) {
    if (isset($_GET['user_id'])) {
        $user_id = $_GET["user_id"];
        $data = (new OrderController())->getByUser($user_id);
        // $data == 1001 ?  $code = 1001 :  $code = 1000;

        if ($data == 1001) {
            $code = 1001;
            $data = null;
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
