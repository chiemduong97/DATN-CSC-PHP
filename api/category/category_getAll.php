<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/category_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';

    $authen = new Authen();
    if ($authen->checkToken()) {
        (new CF_Header()) -> config("GET");
        $data = (new CategoryController()) -> getAll();
        echo json_encode($data);
    } else {
        echo null;
    }
    
?>