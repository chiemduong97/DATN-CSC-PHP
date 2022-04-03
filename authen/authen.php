<?php
    use Firebase\JWT\JWT;
    include_once $_SERVER['DOCUMENT_ROOT'].'/libs/php-jwt-master/src/BeforeValidException.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/libs/php-jwt-master/src/ExpiredException.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/libs/php-jwt-master/src/JWT.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/user_service.php';

    Class Authen{
        public function __construct(){
            
        }
        public function getAuthorizationHeader(){
            $headers = null;
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            }
            else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
                // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                //print_r($requestHeaders);
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
        }
        public function getBearerToken() {
            $headers = $this -> getAuthorizationHeader();
            // HEADER: Get the access token from the header
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            }
            return null;
        }

        public function checkToken(){
            try{
                $token = $this -> getBearerToken();
                if($token){
                    $jwt = JWT::decode($token,123,array_keys(JWT::$supported_algs));
                    $email = $jwt -> data -> email;
                    $service = new UserService();
                    if($service -> checkEmail($email) == 1000){
                        return true;
                    }
                }
            }catch(Exception $e){
                
            }
            return false;
        }
    }
    
?>