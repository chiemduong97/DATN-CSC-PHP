<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/createTables.php';
    include_once '../../config/configHeader.php';

    (new CF_Header()) -> config("GET");

    $data = (new CreateTables()) -> createTables();

    echo json_encode(array(
        "status"=>$data,
    ));


?>