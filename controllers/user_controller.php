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
            $check = password_verify($password, $user->password);
            if ($check) {
                if ($user->status == 0) {
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
                $refreshToken = '1//0eE4HEd_6qQgvCgYIARAAGA4SNwF-L9Irfz5SKXuf6xsmhhXrLNB5LHQFgD_XHoRiuO0kf6h4PBTdUxQlGgRiL04suLjU6RbR4qQ';
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
                $mail->isHTML(true);  // Set email format to HTML
                $mail->Subject = $requestType . " Request";
                $mail->CharSet = PHPMailer::CHARSET_UTF8;
                $description = "Chào " . $email . "!<br>Mã xác nhận của bạn là <b>" . $code  . "</b>.<br>Mã khả dụng trong 5 phút.<br>Cảm ơn!";
                $mail->Body = $description;
                if (!$mail->send()) {
                    return 1001;
                } else {
                    return 1000;
                }
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

    public function updateAvatar($email, $avatar)
    {
        $user = new User();
        $user->email = $email;
        $user->avatar = $avatar;
        return $this->service->updateAvatar($user);
    }

    public function updateInfo($user)
    {
        return $this->service->updateInfo($user);
    }

    public function updateLocation($email, $lat, $lng, $address)
    {
        return $this->service->updateLocation($email, $lat, $lng, $address);
    }

    public function updatePass($email, $oldpassword, $newpassword)
    {
        $user = $this->service->getByEmail($email);
        if ($user) {
            $check = password_verify($oldpassword, $user->password);
            if ($check) {
                $hash = password_hash($newpassword, PASSWORD_BCRYPT);
                $user = new User();
                $user->email = $email;
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
}
