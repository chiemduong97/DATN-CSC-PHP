<?php
include_once '../../config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/user_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("POST");
$code = 1001;
$data = [];
$authen = new Authen();

if ($authen->checkToken()) {
    $body = json_decode(file_get_contents('php://input'));
    $data = (new UserController())->updateInfo($body);
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
