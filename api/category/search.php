<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/category_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("GET");

$code = 1001;
$data = [];
$load_more = false;
$total = 0;

$authen = new Authen();

if ($authen->checkToken()) {
    $page = 1;
    $limit = 10;

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if (isset($_GET['limit'])) {
        $limit = $_GET['limit'];
    }

    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }

    if ($query == "") {
        $code = 1013;
    } else {
        $total = (new CategoryController())->getTotalPageSearch($query);
        $data = (new CategoryController())->search($query, $page, $limit);
        is_null($data) ? $code = 1001 : $code = 1000;
    }
} else {
    $code = 401;
}

echo (
    (new Response(
        $code,
        $data,
        $load_more,
        $total
    ))->response()
);
