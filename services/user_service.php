<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/user_model.php';


class UserService
{
    private $db;
    private $users = "users";
    public function __construct()
    {
        $this->db = (new DatabaseConfig())->db_connect();
    }

    // dang co loi: dang ky sdt da ton tai se crash -> kiem tra sdt va email trong controller truoc
    public function register($user)
    {
        try {
            $sql = 'UPDATE requests SET status=0 WHERE email=:email';
            $stmt = $this->db->prepare($sql);
            $email = $user->email;
            $password = $user->password;
            $fullname = $user->fullname;
            $phone = $user->phone;
            $permission = $user->permission;
            $stmt->bindParam(":email", $email);
            $this->db->beginTransaction();
            if ($stmt->execute()) {
                $this->db->commit();
                $sql = "insert into " . $this->users . " set email=:email,password=:password,fullname=:fullname,phone=:phone,permission=:permission";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":password", $password);
                $stmt->bindParam(":fullname", $fullname);
                $stmt->bindParam(":phone", $phone);
                $stmt->bindParam(":permission", $permission);
                $this->db->beginTransaction();
                if ($stmt->execute()) {
                    $this->db->commit();
                    return 1000;
                } else {
                    $this->db->rollBack();
                    return 1001;
                }
            } else {
                $this->db->rollBack();
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
            return 1001;
        }
    }

    public function checkEmail($email)
    {
        try {
            $sql = "select id from " . $this->users . " where email=:email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return 1000;
            } 
            return 1002;
        } catch (Exception $e) {
            throw $e;
            return 1001;
        }
        return 1001;
    }

    public function checkPhone($phone)
    {
        try {
            $sql = "select id from " . $this->users . " where phone=:phone";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":phone", $phone);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return 1000;
            } 
            return 1015;
        } catch (Exception $e) {
            throw $e;
            return 1001;
        }
        return 1001;
    }

    public function getByEmail($email)
    {
        try {
            $sql = "SELECT id,email,password,avatar,fullname,phone,birthday,wallet,csc_point,status,permission,first_order,device_token,created_at FROM " . $this->users . " WHERE email=:email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract(array($row));
                $user = new User();
                $user->id = $row["id"];
                $user->email = $row["email"];
                $user->password = $row["password"];
                $user->avatar = $row["avatar"];
                $user->fullname = $row["fullname"];
                $user->birthday = $row["birthday"];
                $user->phone = $row["phone"];
                $user->status = $row["status"];
                $user->permission = $row["permission"];
                $user->first_order = $row["first_order"];
                $user->wallet = $row["wallet"];
                $user->csc_point = $row["csc_point"];
                $user->device_token = $row["device_token"];
                $user->created_at = $row["created_at"];
                return $user;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function verification($email, $code)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $sql = 'SELECT salt,token,status,created_at FROM requests WHERE email=:email';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            if ($stmt->execute()) {
                $data = $stmt->fetchObject();
                $salt = $data->salt;
                $token = $data->token;
                $status = $data->status;
                if ($status == 1) {
                    if ($this->verifyHash($code . $salt, $token)) {
                        $old = new DateTime($data->created_at);
                        $now = new DateTime(date("Y-m-d H:i:s"));
                        $diff = $now->getTimestamp() - $old->getTimestamp();
                        if ($diff < 300) {
                            return 1000;
                        } else {
                            return $diff;
                        }
                    } else {
                        return 1007;
                    }
                } else {
                    return 1009;
                }
            } else {
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 1001;
    }

    public function getHash($password)
    {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = password_hash($password . $salt, PASSWORD_BCRYPT);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
    public function verifyHash($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function createRequest($email)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $random_string = substr(str_shuffle(str_repeat("0123456789", 6)), 0, 6);
            $hash = $this->getHash($random_string);
            $token = $hash["encrypted"];
            $salt = $hash["salt"];
            $sql = 'SELECT COUNT(*) from requests WHERE email=:email';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":email", $email);
            if ($stmt->execute()) {
                $row_count = $stmt->fetchColumn();
                if ($row_count == 0) {
                    $sql = 'INSERT INTO requests SET email=:email,token=:token,salt=:salt,created_at=:created_at';
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":token", $token);
                    $stmt->bindParam(":salt", $salt);
                    $created_at = date("Y-m-d H:i:s");
                    $stmt->bindParam(":created_at", $created_at);
                    $this->db->beginTransaction();
                    if ($stmt->execute()) {
                        $this->db->commit();
                        return $random_string;
                    } else {
                        $this->db->rollBack();
                        return null;
                    }
                } else {
                    $sql = 'SELECT COUNT(*) from requests WHERE email=:email and status=1 and created_at > now() - interval 5 minute';
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(":email", $email);
                    if ($stmt->execute()) {
                        $row_count = $stmt->fetchColumn();
                        if ($row_count == 0) {
                            $sql = 'UPDATE requests SET token =:token,salt =:salt,created_at=:created_at,status=1 
                                    WHERE email=:email';
                            $stmt = $this->db->prepare($sql);
                            $stmt->bindParam(":email", $email);
                            $stmt->bindParam(":token", $token);
                            $stmt->bindParam(":salt", $salt);
                            $created_at = date("Y-m-d H:i:s");
                            $stmt->bindParam(":created_at", $created_at);
                            $this->db->beginTransaction();
                            if ($stmt->execute()) {
                                $this->db->commit();
                                return $random_string;
                            } else {
                                $this->db->rollBack();
                                return null;
                            }
                        } else {
                            return 1010;
                        }
                    } else {
                        return null;
                    }
                }
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function resetPassword($email, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User(null, $email, $hash, null, null, null, null, null, null, null, null, null, null);
        $sql = "UPDATE requests SET status=0 WHERE email=:email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":email", $email);
        $this->db->beginTransaction();
        if ($stmt->execute()) {
            $this->db->commit();
            return $this->updatePass($user);
        } else {
            $this->db->rollBack();
            return 1001;
        }
    }

    public function updatePass($user)
    {
        try {
            $sql = "update " . $this->users . " set password=:password where email=:email";
            $stmt = $this->db->prepare($sql);
            $password = $user->password;
            $email = $user->email;
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":email", $email);

            $this->db->beginTransaction();
            if ($stmt->execute()) {
                $this->db->commit();
                return 1000;
            } else {
                $this->db->rollBack();
                return 1001;
            }
        } catch (Exception $e) {
            // throw $e;
            return 1001;
        }
    }

    public function getUserByEmail($email)
    {
        try {
            $sql = "SELECT id,email,avatar,fullname,phone,birthday,wallet,csc_point,first_order FROM " . $this->users . " where email=:email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract(array($row));
                $data = array(
                    "id" => $row["id"],
                    "email" => $row["email"],
                    "avatar" => $row["avatar"],
                    "fullname" => $row["fullname"],
                    "phone" => $row["phone"],
                    "birthday" => $row["birthday"],
                    "wallet" => $row["wallet"],
                    "csc_point" => $row["csc_point"],
                    "first_order" => $row["first_order"]
                );
                return $data;
            }
            return null;
        } catch (Throwable $e) {
            throw $e;
            return null;
        }
    }

    public function updateAvatar($user)
    {
        try {
            $sql = "update " . $this->users . " set avatar=:avatar where email=:email";
            $stmt = $this->db->prepare($sql);
            $avatar = $user->avatar;
            $email = $user->email;
            $stmt->bindParam(":avatar", $avatar);
            $stmt->bindParam(":email", $email);

            $this->db->beginTransaction();
            if ($stmt->execute()) {
                $this->db->commit();
                return 1000;
            } else {
                $this->db->rollBack();
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
            return 1001;
        }
        return 1001;
    }

    public function updateInfo($body)
    {
        try {
            $sql = "UPDATE " . $this->users . " SET fullname=:fullname,
                                                    birthday=:birthday,
                                                    phone=:phone WHERE email=:email";
            $stmt = $this->db->prepare($sql);
            $fullname = $body->fullname;
            $birthday = $body->birthday;
            $phone = $body->phone;
            $email = $body->email;

            $stmt->bindParam(":fullname", $fullname);
            $stmt->bindParam(":birthday", $birthday);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":email", $email);

            $this->db->beginTransaction();
            if ($stmt->execute()) {
                $this->db->commit();
                return 1000;
            } else {
                $this->db->rollBack();
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 1001;
    }

    public function updateDeviceToken($email, $device_token)
    {
        try {
            $sql = "UPDATE " . $this->users . " SET device_token=:device_token WHERE email=:email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":device_token", $device_token);
            $stmt->bindParam(":email", $email);
            $this->db->beginTransaction();
            if ($stmt->execute()) {
                $this->db->commit();
                return 1000;
            } else {
                $this->db->rollBack();
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 1001;
    }
}
