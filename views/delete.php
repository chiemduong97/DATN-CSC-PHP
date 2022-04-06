<?php
require_once '../include/DB_Functions.php';
$db = new DB_Functions();

session_start();

if (empty($_SESSION['id'])) {
    echo '<center>Hãy đăng nhập...</center>';
    header('Refresh: 2; url= login.php');
    return;
}

if (!empty($_GET['id'])) {

    $idDelete = $_GET['id'];
    $response = $db->deleteCategory($idDelete);
    // echo $response;
    header('Refresh: 0; url= category.php');
}
