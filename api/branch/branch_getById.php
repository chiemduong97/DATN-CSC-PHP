<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/branch_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

$code = 400;
$data = null;

$authen = new Authen();

if ($authen->checkToken()) {
    if (isset($_GET['id'])) {
        $id = $_GET["id"];
        $data = (new BranchController())->getById($id);

        if ($data) {
            $code = 1000;
        } else {
            $code = 1001;
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
    ))->response()
);
