<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/category_controller.php';

    (new CF_Header()) -> config("GET");

    $data = (new CategoryController()) -> getAll();
    
    if($data != 1001){
        echo json_encode($data);
    }else{
        echo json_encode(array(
            "status"=>false,
            "code"=>$data
        ));
    }
?>