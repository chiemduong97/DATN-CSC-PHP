<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/category_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';

(new CF_Header())->config("POST");
$code = 1001;
$data = null;
$authen = new Authen();

if ($authen->checkToken()) {
    $id = isset($_POST["id"]) ? $_POST["id"] : -1;
    $data = (new CategoryController())->removeItem($id);

    if ($data == 1000) {
        $code = 1000;
    } else {
        $code = $data;
    }
    $data = null;
} else {
    $code = 401;
}

echo (
    (new Response(
        $code,
        $data
    ))->response()
);
