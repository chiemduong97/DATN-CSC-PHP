<?php
include_once '../../config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/user_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("POST");
$authen = new Authen();
$code = 1001;
$data = [];

if ($authen->checkToken()) {
    if (
        isset($_POST["email"]) && isset($_POST["oldpassword"]) &&
        isset($_POST["newpassword"])
    ) {
        $email = $_POST["email"];
        $oldpassword = $_POST["oldpassword"];
        $newpassword = $_POST["newpassword"];

        $data = (new UserController())->updatePass($email, $oldpassword, $newpassword);
        $data == 1000 ?  $code = 1000 :   $code = $data;
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
