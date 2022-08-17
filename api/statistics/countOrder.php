<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/statistics_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("GET");

$code = 1001;
$data = [];
$total = 0;
$load_more = false;
$authen = new Authen();

if ($authen->checkToken()) {
    $data = (new StatisticsController()) -> countOrder();
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
