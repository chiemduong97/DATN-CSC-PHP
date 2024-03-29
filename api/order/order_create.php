<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("POST");
$body = json_decode(file_get_contents("php://input"));

$authen = new Authen();
$code = 400;
$data = null;

if ($authen->checkToken()) {
    $data = (new OrderController())->insertItem($body);
    if (is_null($data)) {
        $code = 1001;
    } else {
        $code = 1000;
        $data = array(
            "order_code" => $data
        );
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
