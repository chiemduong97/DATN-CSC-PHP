<?php

use PHPMailer\PHPMailer\PHPMailer;

include_once $_SERVER['DOCUMENT_ROOT'] . '/services/user_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/PHPMailer-master/src/PHPMailer.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/PHPMailer-master/src/SMTP.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/PHPMailer-master/src/Exception.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/user_model.php';


class UserController
{
    private $service;
    public function __construct()
    {
        $this->service = new UserService();
    }

    public function checkEmail($email){
        return $this -> service -> checkEmail($email);
    }

    public function getUserByEmail($email){
        return $this -> service -> getUserByEmail($email);
    }

    public function register($fullname,$phone, $email, $password, $permission)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User(null, $email, $hash, null, $fullname, null, $phone, null, $permission, null, null, null,null);
        $result = $this->service->register($user);
        return $result;
    }

    public function login($email, $password)
    {
        $user = $this->service->getByEmail($email);
        if ($user) {
            $check = password_verify($password, $user->getPassword());
            if ($check) {
                if ($user->getStatus() == 0) {
                    return 1005;
                } else {
                    return 1000;
                }
            } else {
                return 1004;
            }
        }
        return 1001;
    }

    public function sendRequest($email, $requestType)
    {
        $checkEmail = $this->service->checkEmail($email);
        if ($requestType == "REGISTER" && $checkEmail == 1000) {
            return 1003;
        }
        $code = $this->service->createRequest($email);
        if ($code == 1010) {
            return $code;
        }
        if ($code != null) {
            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = 0;  // 0,1,2: chế độ debug. khi mọi cấu hình đều tớt thì chỉnh lại 0 nhé
                $mail->isSMTP();
                $mail->CharSet  = "utf-8";
                $mail->Host = 'smtp.gmail.com';  //SMTP servers
                $mail->SMTPAuth = true; // Enable authentication
                $from = 'chiemduong01@gmail.com';
                $pass = 'emtenduong97';
                $from_name = 'Đỗ Chiếm Dương';
                $mail->Username = $from; // SMTP username
                $mail->Password = $pass;   // SMTP password
                $mail->SMTPSecure = 'ssl';  // encryption TLS/SSL 
                $mail->Port = 465;  // port to connect to                
                $mail->setFrom($from, $from_name);
                $to = $email;
                $to_name = $email;
                $mail->addAddress($to, $to_name); //mail và tên người nhận  
                $mail->isHTML(true);  // Set email format to HTML
                $mail->Subject = $requestType . " Request";
                $description = "Chào " . $to . "!<br>Mã xác nhận của bạn là <b>" . $code  . "</b>.<br>Mã khả dụng trong 5 phút.<br>Cảm ơn!";
                $mail->Body = $description;
                $mail->smtpConnect(array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true
                    )
                ));
                $mail->send();
                return 1000;
            } catch (Exception $e) {
                throw $e;
            }
        }
        return 1001;
    }

    public function verification($email, $code)
    {
        return $this->service->verification($email, $code);
    }

    public function resetPassword($email, $password)
    {
        return $this->service->resetPassword($email, $password);
    }

    public function updateAvatar($email,$avatar){
        $user = new User(null,$email,null,$avatar,null,null,null,null,null,null,null,null,null);
        return $this -> service -> updateAvatar($user);
    }

    public function updateInfo($user){
        return $this -> service -> updateInfo($user);
    }

    public function updatePass($email,$oldpassword,$newpassword){
        $user = $this -> service -> getByEmail($email);
        if($user){
            $check = password_verify($oldpassword,$user -> getPassword());
            if($check){
                $hash = password_hash($newpassword,PASSWORD_BCRYPT);
                $user = new User(null,$email,$hash,null,null,null,null,null,null,null,null,null,null);
                return $this -> service -> updatePass($user);
            }
            else{
                return 1006;
            }
        }
        return 1001;
    }

    public function updateDeviceToken($email,$deviceToken){
        return $this -> service -> updateDeviceToken($email,$deviceToken);
    }

    
}
