<?php
include_once '../../config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/user_controller.php';

use Firebase\JWT\JWT;

include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/php-jwt-master/src/BeforeValidException.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/php-jwt-master/src/ExpiredException.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/php-jwt-master/src/JWT.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';
(new CF_Header())->config("POST");
$code = 1001;
$data = [];
if (
    isset($_POST["email"]) && isset($_POST["password"])
    && isset($_POST["phone"]) && isset($_POST["fullname"])
) {

    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $fullname = $_POST["fullname"];

    $token = array(
        "iat" => time(),
        "iss" => "http://localhost:8585",
        "data" => array(
            "email" => $email,
        )
    );
    $jwt = JWT::encode($token, 123);

    $data = (new UserController())->register($fullname, $phone, $email, $password, "0");
    if ($data == 1000) {
        $code = 1000;
        $data = array(
            "access_token" => $jwt
        );
    } else {
        $code =  $data;
        $data = null;
    }
} else {
    $code = 1013;
}
echo (
    (new Response(
        $code,
        $data,
    ))->response()
);