<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/database.php';

    class NotificationService {
        private $db;
        private $notifications = "notifications";
        public function __construct(){
            $this -> db = (new DatabaseConfig()) -> db_connect();
        }

        public function getByUser($user){
            try{
                $sql = "select id,action,description,createAt from " . $this -> notifications 
                . " where user=:user or user is null order by id desc";
                $stmt = $this -> db -> prepare($sql);
                $stmt -> bindParam(":user",$user);
                $stmt -> execute();
                if($stmt -> rowCount() > 0){
                    $data = array();
                    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                        extract($row);
                        $each = array(
                            "id"=>$row['id'],
                            "action"=>$row['action'],
                            "description"=>$row['description'],
                            "createAt"=>$row['createAt']
                        );
                        array_push($data,$each);
                    }
                    return $data;
                }
                else{
                    return null;
                }
            }catch(Exception $e){
                throw $e;
            }
            return null;

        }    
        
        function sendNotification($devicesToken, $notification) {
            $messages = [
                'registration_ids' => $devicesToken,
                'data' => array(
                    "action"=>$notification -> getAction(),
                    "description"=>$notification -> getDescription()
                ),
            ];
       
            $headers = [
                'Authorization: key=' . 'AAAAeovxBpU:APA91bG7UBE0nGoRX8Pm5V1kBljZjx_yFZ99e768dWy1uIqTXKxaV1PTDR2yMUDyk97feldexCafFJXC2vjuOC9UUCEL3oFhsuKhHOg0whKjWscb5NjWLyGK1ZATaqrMsYBL3ch9nKjT',
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
            }
            else{
                try{
                    $sql = "insert into " . $this -> notifications . " set action=:action,description=:description,user=:user,createAt=:createAt";
                            $stmt = $this -> db -> prepare($sql);
                            $user = $notification -> getUser();
                            $action = $notification -> getAction();
                            $description = $notification -> getDescription();
                            $createAt = $notification -> getCreateAt();
                            $stmt -> bindParam(":user",$user);
                            $stmt -> bindParam(":action",$action);
                            $stmt -> bindParam(":description",$description);
                            $stmt -> bindParam(":createAt",$createAt);
                            if($stmt -> execute()){
                                return 1000;
                            }
                            else{
                                return 1001;
                            }
                }catch(Throwable $e){
                    throw $e;
                }
                return 1001;
            }
        }


    }


?>