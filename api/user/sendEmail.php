<?php
include_once '../../config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/user_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("GET");
$code = 1001;
$data = [];

if (
    isset($_GET["email"]) && isset($_GET["requestType"])
) {
    $email = $_GET["email"];
    $requestType = $_GET["requestType"];
    $data = (new UserController())->sendRequest($email, $requestType);
    if ($data == 1000) {
        $code = 1000;
    } else {
        $code = $data;
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
