<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';

    $authen = new Authen();
    if ($authen->checkToken()) {
        $order_code = $_GET["order_code"];
        $data = (new OrderController()) -> getByorder_code($order_code);
        echo json_encode($data);
    } else {
        echo null;
    }
    
?>