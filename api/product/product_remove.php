<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/product_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';

(new CF_Header())->config("DELETE");
$code = 400;
$data = 1001;
$authen = new Authen();

$body = json_decode(file_get_contents("php://input"));

if ($authen->checkToken()) {
    if (property_exists($body, 'id')) {
        $id = $body->id;

        $data = (new ProductController())->removeItem($id);

        if ($data == 1000) {
            $code = 1000;
        } else {
            $code = $data;
        }
        $data = null;
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
