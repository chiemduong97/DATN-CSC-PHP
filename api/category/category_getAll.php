<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/category/category_controller.php';

    (new CF_Header()) -> config("GET");

    $data = (new CategoryController()) -> getAll();
    echo json_encode($data);
    
?>