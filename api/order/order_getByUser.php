<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("GET");

$code = 1001;
$data = [];
$total = 0;
$load_more = false;
$authen = new Authen();

if ($authen->checkToken()) {
    if (isset($_GET['user_id'])) {
        $user_id = $_GET["user_id"];

        $page = 1;
        $limit = 10;

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        $total = (new OrderController())->getTotalPageByUser(10, $user_id);

        if ($total > $page) {
            $load_more = true;
            $data = (new OrderController())->getByUser($user_id,$page, $limit);
            is_null($data) ? $code = 1001 : $code = 1000;
        }  
        if ($total == $page) {
            $load_more = false;
            $data = (new OrderController())->getByUser($user_id,$page, $limit);
            is_null($data) ? $code = 1001 : $code = 1000;
        } 
        if ($total < $page) {
            $load_more = false;
            $code = 1000;
            $data = [];
        }

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
        $load_more,
        $total
    ))->response()
);
