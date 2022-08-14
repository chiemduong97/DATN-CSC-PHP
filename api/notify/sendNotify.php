<?php
include_once '../../config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/notify_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';

(new CF_Header())->config("POST");
$authen = new Authen();
$code = 1001;
$data = [];

if ($authen->checkToken()) {
    $action = $_POST["action"];
    $description = $_POST["description"];

    if ($action != "" && $description != "") {
        $data = (new NotifyController())->sendNotify($action, $description);
        if ($data == 1000) {
            $code = 1000;
        } else {
            $code = $data;
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
        null,
    ))->response()
);
