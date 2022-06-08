<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/product_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

$authen = new Authen();
(new CF_Header())->config("GET");

$code = 1001;
$data = [];
$load_more = false;

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

        $total = (new ProductController())->getTotalPages($category_id, $branch_id, $limit);
        if ($total > $page) {
            $load_more = true;
        } else if ($total == $page) {
            $load_more = false;
        } else if ($total < $page) {
            $load_more = false;
            return;
        }
        $data = (new ProductController())->getProducts($category_id, $branch_id, $page, $limit);
        $data ? $code = 1000 : $code = 1001;
    } else {
        $code = 1013;
    }
} else {
    $code = 401;
}

echo (
    (new Response(
        $code,
        $data,
        $load_more
    ))->response()
);
