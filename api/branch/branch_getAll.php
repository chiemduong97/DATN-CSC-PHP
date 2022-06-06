<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/branch_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header()) -> config("GET");

$code = 400;
$data = [];

$authen = new Authen();

if ($authen->checkToken()) {
    $data = (new BranchController())->getAll();
    if ($data) {
        $code = 1000;
    } else {
        $code = 1001;
    }
} else {
    $code = 401;
}

echo (
    (new Response(
        $code,
        $data,
    ))->response()
);
