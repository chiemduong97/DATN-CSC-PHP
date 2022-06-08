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
        isset($_POST["email"]) && isset($_POST["lat"]) &&
        isset($_POST["lng"]) && isset($_POST["address"])
    ) {
        $email = $_POST["email"];
        $lat = $_POST["lat"];
        $lng = $_POST["lng"];
        $address = $_POST["address"];

        $data = (new UserController())->updateLocation($email, $lat, $lng, $address);
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
        null,
    ))->response()
);
