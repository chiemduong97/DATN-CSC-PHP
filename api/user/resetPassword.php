<?php
include_once '../../config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/user_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("POST");
$authen = new Authen();
$code = 1001;
$data = [];

if (
    isset($_POST["email"]) && isset($_POST["password"])
) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $data = (new UserController())->resetPassword($email, $password);
    $data == 1000 ?  $code = 1000 :   $code = $data;
} else {
    $code = 1013;
}

echo (
    (new Response(
        $code,
        null,
    ))->response()
);
