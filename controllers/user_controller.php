<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/vendor/autoload.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/user_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/vendor/phpmailer/phpmailer/src/PHPMailer.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/vendor/phpmailer/phpmailer/src/SMTP.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/vendor/phpmailer/phpmailer/src/Exception.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/user_model.php';


class UserController
{
    private $service;
    public function __construct()
    {
        $this->service = new UserService();
    }

    public function checkEmail($email)
    {
        return $this->service->checkEmail($email);
    }

    public function getUserByEmail($email)
    {
        return $this->service->getUserByEmail($email);
    }

    public function register($fullname, $phone, $email, $password, $permission)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User();
        $user->email = $email;
        $user->password = $hash;
        $user->fullname = $fullname;
        $user->phone = $phone;
        $user->permission = $permission;
        $result = $this->service->register($user);
        return $result;
    }

    public function login($email, $password)
    {
        $user = $this->service->getByEmail($email);
        if ($user) {
            $check = password_verify($password, $user["password"]);
            if ($check) {
                if ($user["status"] == 0) {
                    return 1005;
                } else {
                    return 1000;
                }
            } else {
                return 1004;
            }
        }
        return 1002;
    }

    public function sendRequest($email, $phone, $requestType)
    {
        $checkEmail = $this->service->checkEmail($email);
        $checkPhone = $this->service->checkPhone($phone);
        if ($requestType == "REGISTER") {
            if ($checkEmail == 1000) {
                return 1003;
            }
            if ($checkPhone == 1000) {
                return 1015;
            }  
        }
        $code = substr(str_shuffle(str_repeat("0123456789", 6)), 0, 6);
        $mail = new PHPMailer();
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth = true;
            $mail->AuthType = 'XOAUTH2';
            $mail->SMTPSecure = 'ssl'; 
            $from = 'chiemduong01@gmail.com';
            $clientId = '260730311274-42j6eu5hek5vbaej9ml64l2am316t79c.apps.googleusercontent.com';
            $clientSecret = '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';
            $refreshToken = '1//0elOrKYvjMPYtCgYIARAAGA4SNwF-L9IrSG9QwT8sj7-hADsRqqUsWMN842Rp4HWunEaN5KWoiqjE56T3A3jiQeOv3p9kog6r-2k';
            $provider = new Google(
                [
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                ]
            );
            $mail->setOAuth(
                new OAuth(
                    [
                        'provider' => $provider,
                        'clientId' => $clientId,
                        'clientSecret' => $clientSecret,
                        'refreshToken' => $refreshToken,
                        'userName' => $from,
                    ]
                )
            );
            $from_name = 'Đỗ Chiếm Dương';
            $mail->setFrom($from, $from_name);
            $mail->addAddress($email, $email);
            $mail->isHTML(true); 
            $mail->Subject = $requestType . " Request";
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $description = "Chào " . $email . "!<br>Mã xác nhận của bạn là <b>" . $code  . "</b>.<br>Mã khả dụng trong 5 phút.<br>Cảm ơn!";
            $mail->Body = $description;
            if (!$mail->send()) {
                return 1001;
            } else {
                return $code = $this->service->createRequest($email, $code);
            }
        } catch (Exception $e) {
            throw $e;
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

    public function updateAvatar($body)
    {
        $user = new User();
        $user->email = $body->email;
        $user->avatar = $body->avatar;
        return $this->service->updateAvatar($user);
    }

    public function updateInfo($body)
    {
        $checkEmail = $this->service-> checkEmail($body->phone);
        if ($checkEmail == 1000) {
            return 1015;
        }
        return $this->service->updateInfo($body);
    }

    public function updatePass($body)
    {
        $user = $this->service->getByEmail($body->email);
        if ($user) {
            $check = password_verify($body->old_password, $user["password"]);
            if ($check) {
                $hash = password_hash($body->new_password, PASSWORD_BCRYPT);
                $user = new User();
                $user->email = $body->email;
                $user->password = $hash;
                return $this->service->updatePass($user);
            } else {
                return 1006;
            }
        }
        return 1001;
    }

    public function updateDeviceToken($email, $device_token)
    {
        return $this->service->updateDeviceToken($email, $device_token);
    }

    //admin

    public function login_admin($email, $password)
    {
        $user = $this->service->getByEmail($email);
        if ($user["permission"] < 1) return 1020;
        if ($user) {
            $check = password_verify($password, $user["password"]);
            if ($check) {
                if ($user["status"] == 0) {
                    return 1005;
                } else {
                    return 1000;
                }
            } else {
                return 1004;
            }
        }
        return 1002;
    }

    public function getAll($permission,$page,$limit)
    {
        return $this->service->getAll($permission,$page,$limit);
    }

    public function getTotalPage($permission,$limit){
        return $this -> service -> getTotalPage($permission,$limit);
    }

    public function search($permission,$page,$limit,$query)
    {
        return $this->service->search($permission,$page,$limit,$query);
    }

    public function getTotalPageSearch($permission,$limit,$query){
        return $this -> service -> getTotalPageSearch($permission,$limit,$query);
    }

    public function updateStatus($email){
        return $this -> service -> updateStatus($email);
    }

    public function updatePermission($email){
        return $this -> service -> updatePermission($email);
    }
    

}
