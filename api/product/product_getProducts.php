<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/product_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

$authen = new Authen();
(new CF_Header())->config("GET");

$code = 400;
$data = [];
$isError = true;
$message = "Invalid";

if ($authen->checkToken()) {
    if (
        isset($_GET['category_id']) && isset($_GET['branch_id'])
    ) {
        $category_id = $_GET['category_id'];
        $branch_id = $_GET['branch_id'];
        $page = 1;
        $limit = 10;

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        $data = (new ProductController())->getProducts($category_id, $branch_id, $page, $limit);
        $code = 200;
        $isError = false;
        $message = "Success";
    } else {
        $message =  "Please fill out completely !";
    }
} else {
    $code = 401;
    $message =   "Unauthorized !";
}

echo (
    (new Response(
        $code,
        $data,
        $isError,
        $message
    ))->response()

);
