<?php
    include_once '../../config/configHeader.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/user_controller.php';
    use Firebase\JWT\JWT;
    include_once $_SERVER['DOCUMENT_ROOT'].'/libs/php-jwt-master/src/BeforeValidException.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/libs/php-jwt-master/src/ExpiredException.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/libs/php-jwt-master/src/JWT.php';

    
    (new CF_Header()) -> config("POST");
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];


    $token = array(
        "iat"=>time(),
        "iss"=>"http://localhost:8585",
        "data"=> array(
            "email"=>$email,
        )
    );
    $jwt = JWT::encode($token,123);

    $data = (new UserController()) -> register($phone,$email,$password,"2");
    if($data == 1000){
        echo json_encode(array(
            "status"=>true,
            "accessToken"=>$jwt
        ));
    }
    else{
        echo json_encode(array(
            "status"=>false,
            "code"=>$data
        ));
    }
?>