<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/category_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("GET");

$code = 400;
$data = [];

$authen = new Authen();

if ($authen->checkToken()) {
    if (isset($_GET['category_id'])) {
        $category_id = $_GET['category_id'];
        $data = (new CategoryController())->getCategoriesLevel_1($category_id);
        is_null($data) ? $code = 1001 : $code = 1000;

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
