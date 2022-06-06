<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/category_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';

(new CF_Header())->config("POST");
$authen = new Authen();
$body = json_decode(file_get_contents("php://input"));

$code = 400;
$data = null;

if ($authen->checkToken()) {
    if (property_exists($body, 'name') && property_exists($body, 'avatar')) {
        $name = $body->name;
        $avatar = $body->avatar;
        $data = (new CategoryController())->insertItem($name, $avatar);

        if ($data == 1000) {
            $code = 1000;
        } else {
            $code = 1001;
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
        $data
    ))->response()
);
