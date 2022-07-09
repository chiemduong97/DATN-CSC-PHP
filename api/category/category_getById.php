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
    if (isset($_GET['id'])) {
        $id = $_GET["id"];
        $data = (new CategoryController())->getByID($id);
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
