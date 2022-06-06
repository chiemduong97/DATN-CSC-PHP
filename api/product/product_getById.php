<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/product_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';

(new CF_Header())->config("POST");
$authen = new Authen();
$body = json_decode(file_get_contents("php://input"));

$code = 400;
$data = [];

if ($authen->checkToken()) {
    if (property_exists($body, 'id')) {
        $id = $body->id;
        $data = (new ProductController())->getByID($id);

        if ($data != 1001) {
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
