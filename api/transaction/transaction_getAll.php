<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/transactions_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

$authen = new Authen();
(new CF_Header())->config("GET");

$code = 1001;
$data = null;
$load_more = false;
$total = 0;

if ($authen->checkToken()) {
    if (isset($_GET['user_id']) && isset($_GET['type'])) {
        $user_id = $_GET['user_id'];
        $type = $_GET['type'];
        $page = 1;
        $limit = 10;

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        $total = (new TransactionController())->getTotalPages($type, $user_id, $limit);

        if ($total > $page) {
            $load_more = true;
            // ---------------------------------------------------
            $data = (new TransactionController())->getTransaction($type, $user_id, $page, $limit);
            is_null($data) ? $code = 1001 : $code = 1000;
        }
        if ($total == $page) {
            $load_more = false;
            // ---------------------------------------------------
            $data = (new TransactionController())->getTransaction($type, $user_id, $page, $limit);
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
};

echo (
    (new Response(
        $code,
        $data,
        $load_more,
        $total
    ))->response()
);
