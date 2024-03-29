<?php
include_once '../../config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/user_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("POST");
$code = 1001;
$data = [];

if (
    isset($_POST["email"]) && isset($_POST["code"])
) {
    $email = $_POST["email"];
    $code = $_POST["code"];
    $data = (new UserController())->verification($email, $code);
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
