<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';

    $authen = new Authen();
    if ($authen->checkToken()) {
        $user_id = $_GET["user_id"];
        $data = (new OrderController()) -> getByUser($user_id);
        echo json_encode($data);
    } else {
        echo null;
    }
    
?>