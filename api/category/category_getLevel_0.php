<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/category_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header()) -> config("GET");

$code = 1001;
$data = [];

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
   
    $data = (new CategoryController()) -> getCategoriesLevel_0($page, $limit);
    
    $data ? $code = 1000 : $code = 1001;
    
} else {
    $code = 401;
}

echo (
    (new Response(
        $code,
        $data
    ))->response()
);
