<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/product_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

$authen = new Authen();
(new CF_Header())->config("GET");

$code = 1001;
$data = null;
$total = 0;
$load_more = false;

if ($authen->checkToken()) {
    $page = 1;
    $limit = 10;

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if (isset($_GET['limit'])) {
        $limit = $_GET['limit'];
    }

    $total = (new ProductController())->getTotalPagesHighLight($limit);

    if ($total > $page) {
        $load_more = true;
        // ---------------------------------------------------
        $data = (new ProductController())->getProductsHighLight($page, $limit);
        is_null($data) ? $code = 1001 : $code = 1000;
    }
    if ($total == $page) {
        $load_more = false;
        // ---------------------------------------------------
        $data = (new ProductController())->getProductsHighLight($page, $limit);
        is_null($data) ? $code = 1001 : $code = 1000;
    }
    if ($total < $page) {
        $load_more = false;
        $code = 1000;
        $data = [];
    }
} else {
    $code = 401;
};

echo (
    (new Response(
        $code,
        $data,
        $load_more,
        $total
    ))->response()
);
