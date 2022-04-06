<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/product_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';
$authen = new Authen();
(new CF_Header())->config("GET");
if ($authen->checkToken()) {
    if ($_GET['category'] != null) {
        $category = $_GET['category'];
        $data = (new ProductController())->getByCategory($category);
        echo json_encode($data);
    } 
    else {
        echo null;
    }
}
else {
    echo null;
}
