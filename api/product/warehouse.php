<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/product_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';

(new CF_Header())->config("POST");
$authen = new Authen();
$body = json_decode(file_get_contents("php://input"));

$code = 400;
$data = null;

// if ($authen->checkToken()) {
    if (
        property_exists($body, 'product_id') &&
        property_exists($body, 'quantity') &&
        property_exists($body, 'email')
    ) {
        $product_id = $body->product_id;
        $quantity = $body->quantity;
        $email = $body->email;
        $data = (new ProductController())->warehouse($product_id,$quantity,$email);

        if ($data == 1000) {
            $code = 1000;
        } else {
            $code = $data;
        }
        $data = null;
    } else {
        $code = 1013;
    }
// } else {
//     $code = 401;
// }

echo (
    (new Response(
        $code,
        $data
    ))->response()
);
