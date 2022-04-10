<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/branch_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';

    $authen = new Authen();
    if ($authen->checkToken()) {
        $id = $_GET["id"];
        $data = (new BranchController()) -> getById($id);
        echo json_encode($data);
    } else {
        echo null;
    }
    
?>