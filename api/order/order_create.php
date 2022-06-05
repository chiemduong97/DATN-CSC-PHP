<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/order_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';

    $authen = new Authen();
    if ($authen->checkToken()) {
        (new CF_Header()) -> config("POST");

        $orderParam = json_decode(file_get_contents("php://input"));    
        $data = (new OrderController()) -> insertItem($orderParam);
        if($data != null){
            echo json_encode(array(
                "status"=>true,
                "order_code"=>$data
            ));
        }
        else{
            echo json_encode(array(
                "status"=>false,
                "code"=>1001
            ));
        }
    } else {
        echo json_encode(array(
            "status"=>false,
            "code"=>1001
        ));
    }
