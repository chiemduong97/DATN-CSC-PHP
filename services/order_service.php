<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/order_model.php';

class OrderSerivce
{
    private $connection;
    private $orders = "orders";


    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function getByUser($user_id) {
        try {
            $query = "SELECT ordercode,status,amount,address,shippingFee,promotionCode,promotionValue,user_id,branch_id,promotion_id,createdAt,branch_address from " . $this ->orders . " WHERE user_id = :user_id ORDER BY createdAt DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "ordercode" => $ordercode,
                        "status" => $status,
                        "amount" => $amount,
                        "address" => $address,
                        "shippingFee" => $shippingFee,
                        "promotionCode" => $promotionCode,
                        "promotionValue" => $promotionValue,
                        "user_id" => $user_id,
                        "branch_id" => $branch_id,
                        "promotion_id" => $promotion_id,
                        "createdAt" => $createdAt,
                        "branch_address" => $branch_address
                    );
                    array_push($data, $each);
                }
                return $data;
            }
            return array();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
            return null;
        }
        return null;
    }

    public function updateStatus($ordercode,$status) {
        try{
            $sql = "UPDATE " . $this -> orders ." SET status=:status WHERE ordercode=:ordercode";
            $stmt = $this -> connection -> prepare($sql);
            $stmt -> bindParam(":ordercode",$ordercode);
            $stmt -> bindParam(":status",$status);
            $this -> connection -> beginTransaction();
            if($stmt ->execute()){
                $this -> connection -> commit();
                return 1000;
            }
            else{
                $this -> connection -> rollBack();
                return 1001;
            }
        }catch(Exception $e){
            throw $e;
        }
        return 1001;
    }


    public function getByOrderCode($ordercode) {
        try {
            $query = "SELECT ordercode,status,amount,address,shippingFee,promotionCode,promotionValue,user_id,branch_id,promotion_id,createdAt,branch_address from " . $this ->orders . " WHERE ordercode = :ordercode";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":ordercode", $ordercode);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $data = array(
                    "ordercode" => $ordercode,
                    "status" => $status,
                    "amount" => $amount,
                    "address" => $address,
                    "shippingFee" => $shippingFee,
                    "promotionCode" => $promotionCode,
                    "promotionValue" => $promotionValue,
                    "user_id" => $user_id,
                    "branch_id" => $branch_id,
                    "promotion_id" => $promotion_id,
                    "createdAt" => $createdAt,
                    "branch_address" => $branch_address,
                );
                return $data;
            }
            return array();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
            return null;
        }
        return null;
    }
    public function insertItem($order)
    {

        try {
            if($this -> checkOrderCode($order -> ordercode)) {
                $query = "UPDATE " . $this->orders . " SET amount = :amount,
                                                    latitude = :latitude,
                                                    longitude = :longitude,
                                                    address = :address,
                                                    branch_latitude = :branch_latitude,
                                                    branch_longitude = :branch_longitude,
                                                    branch_address = :branch_address,
                                                    shippingFee = :shippingFee,
                                                    promotionCode = :promotionCode,
                                                    promotionValue = :promotionValue
                                                    WHERE ordercode =:ordercode";

                $amount = $order->amount;
                $latitude = $order->latitude;
                $longitude = $order->longitude;
                $address = $order->address;
                $branch_latitude = $order->branch_latitude;
                $branch_longitude = $order->branch_longitude;
                $branch_address = $order->branch_address;
                $shippingFee = $order->shippingFee;
                $promotionCode = $order->promotionCode;
                $promotionValue = $order->promotionValue;
                $ordercode = $order -> ordercode;

                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(":amount", $amount);
                $stmt->bindParam(":latitude", $latitude);
                $stmt->bindParam(":longitude", $longitude);
                $stmt->bindParam(":address", $address);
                $stmt->bindParam(":branch_latitude", $branch_latitude);
                $stmt->bindParam(":branch_longitude", $branch_longitude);
                $stmt->bindParam(":branch_address", $branch_address);
                $stmt->bindParam(":shippingFee", $shippingFee);
                $stmt->bindParam(":promotionCode", $promotionCode);
                $stmt->bindParam(":promotionValue", $promotionValue);
                $stmt->bindParam(":ordercode", $ordercode);
                $this->connection->beginTransaction();
                if ($stmt->execute()) {
                    $this->connection->commit();
                    return true;
                } else {
                    $this->connection->rollBack();
                    return false;
                }
            } else {
                $query = "INSERT INTO " . $this -> orders . " SET ordercode =:ordercode,
                                                              user_id = :user_id,
                                                              branch_id = :branch_id,
                                                              promotion_id = :promotion_id";
                $ordercode = $order->ordercode;
                $user_id = $order->user_id;
                $branch_id = $order->branch_id;
                $stmt = $this->connection->prepare($query);
                $promotion_id = $order->promotion_id;
                $stmt->bindParam(":ordercode", $ordercode);
                $stmt->bindParam(":user_id", $user_id);
                $stmt->bindParam(":branch_id", $branch_id);
                $stmt->bindParam(":promotion_id", $promotion_id);

                $this->connection->beginTransaction();
                if ($stmt->execute()) {
                    $this->connection->commit();
                    return true;
                } else {
                    $this->connection->rollBack();
                    return false;
                }
            }
            
            
        } catch (Exception $e) {
            throw $e;
        }
        return false;
    }

    public function checkOrderCode($ordercode)
    {
        try {
            $query = "SELECT ordercode from " . $this ->orders . " WHERE ordercode = :ordercode";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":ordercode", $ordercode);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
        return false;
    }
    

}
