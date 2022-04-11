<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';

$authen = new Authen();
if ($authen->checkToken()) {
    (new CF_Header())->config("POST");

    $ordercode = $_POST["ordercode"];
    $status = $_POST["status"];
    if ($status != 0) {
        echo json_encode(array(
            "status" => false,
            "code" => 1014
        ));
    } else {
        $data = (new OrderController())->updateStatus($ordercode, 4);
        if ($data == 1000) {
            echo json_encode(array(
                "status" => true
            ));
        } else {
            echo json_encode(array(
                "status" => false,
                "code" => $data
            ));
        }
    }
} else {
    echo json_encode(array(
        "status" => false,
        "code" => 1001
    ));
}
