<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/user_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header()) -> config("GET");

$code = 400;
$data = [];
$load_more = false;
$authen = new Authen();

if ($authen->checkToken()) {
    $permission = $_GET["permission"];
    $page = 1;
    $limit = 10;

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if (isset($_GET['limit'])) {
        $limit = $_GET['limit'];
    }

    $total = (new UserController())->getTotalPage($permission,$limit);
    $data = (new UserController())->getAll($permission,$page,$limit);
    is_null($data) ? $code = 1001 : $code = 1000;
   
} else {
    $code = 401;
}

echo (
    (new Response(
        $code,
        $data,
        $load_more,
        $total != 0 ? $total : 1
    ))->response()
);
