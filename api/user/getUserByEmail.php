<?php
include_once '../../config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/user_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("GET");
$authen = new Authen();
$code = 1001;
$data = [];

if ($authen->checkToken()) {
    if (isset($_GET["email"])) {
        $email = $_GET["email"];
        $data = (new UserController())->getUserByEmail($email);
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
