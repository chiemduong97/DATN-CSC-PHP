<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';

    $authen = new Authen();
    if ($authen->checkToken()) {
        $ordercode = $_GET["ordercode"];
        $data = (new OrderController()) -> getByOrderCode($ordercode);
        echo json_encode($data);
    } else {
        echo null;
    }
    
?>