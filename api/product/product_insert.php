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
        property_exists($body, 'name') &&
        property_exists($body, 'avatar') &&
        property_exists($body, 'description') &&
        property_exists($body, 'price') &&
        property_exists($body, 'category')
    ) {
        $name = $body->name;
        $avatar = $body->avatar;
        $description = $body->description;
        $price = $body->price;
        $category = $body->category;
        $data = (new ProductController())->insertItem($name, $avatar, $description, $price, $category);

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
