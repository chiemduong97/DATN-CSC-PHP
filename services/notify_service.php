<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/user_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/order_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/notify_model.php';

class NotifyService
{
    private $db;
    private $notifies = "notifies";
    public function __construct()
    {
        $this->db = (new DatabaseConfig())->db_connect();
    }

    public function getByUser($user_id, $page = 1, $limit = 10)
    {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $sql = "SELECT id,action,description,created_at,user_id,order_code FROM " . $this->notifies .
                   " WHERE user_id=:user_id OR user_id IS NULL ORDER BY created_at DESC LIMIT :start , :total";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $row['id'],
                        "action" => $row['action'],
                        "description" => $row['description'],
                        "created_at" => $row['created_at'],
                        "user_id" => $row['user_id'],
                        "order_code" => $row['order_code'],
                    );
                    array_push($data, $each);
                }
                return $data;
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function getTotalPage($user_id, $limit = 10)
    {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->notifies . " WHERE user_id = :user_id OR user_id IS NULL";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $count = $row['total'];
                $totalPage = ceil($count / $limit);
                return $totalPage;
            }
            return 1;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return 1;
        }
    }


    function sendNotify($deviceTokens, $notify)
    {
        $messages = [
            'registration_ids' => $deviceTokens,
            'data' => array(
                "action" => $notify -> action,
                "description" => $notify -> description,
                "order"=> $notify -> order_code
            )
        ];

        $headers = [
            'Authorization: key=' . 'AAAAeovxBpU:APA91bG9K94I4BsuHOt-uwTcoxvY5kr2xMEJcgq_v6wYSvJUo7lBFDPY9DVeFu0q576aumjksmHJJH0AQuO2T7YMJNbUUbTeOn4zkn1aiX15dkqGB9VankSnS3ZCR2OTta2KlI0ll6DP',
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messages));

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === FALSE) {
            throw new Exception('FCM Send Error: '  .  curl_error($ch), 500);
        } else {
            try {
                $sql = "INSERT INTO " . $this->notifies . " SET action = :action, description = :description,
                        user_id = :user_id, order_code = :order_code";
                $stmt = $this->db->prepare($sql);
                $user_id = $notify->user_id;
                $action = $notify->action;
                $description = $notify->description;
                $order_code = $notify->order_code;
                $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $stmt->bindParam(":action", $action);
                $stmt->bindParam(":description", $description);
                $stmt->bindParam(":order_code", $order_code);
                if ($stmt->execute()) {
                    return 1000;
                } else {
                    return 1001;
                }
            } catch (Throwable $e) {
                throw $e;
            }
            return 1001;
        }
    }
}
