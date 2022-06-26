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
    public function getTotalPages($limit = 10)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName;
            $stmt = $this->connection->prepare($query);
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
    public function getByUser($user_id, $page, $limit)
    {
        try {
            $page -= 1;
            $page < 0 ? $page = 0 : $page;
            $start = $page * $limit;

            $query = "SELECT order_code,status,amount,address,
            shipping_fee,promotion_code,promotion_value,user_id,branch_id,
            promotion_id,created_at,branch_address from " . $this->orders . " 
            WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :start , :total";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "order_code" => $order_code,
                        "status" => $status,
                        "amount" => $amount,
                        "address" => $address,
                        "shipping_fee" => $shipping_fee,
                        "promotion_code" => $promotion_code,
                        "promotion_value" => $promotion_value,
                        "user_id" => $user_id,
                        "branch_id" => $branch_id,
                        "promotion_id" => $promotion_id,
                        "created_at" => $created_at,
                        "branch_address" => $branch_address
                    );
                    array_push($data, $each);
                }
                return $data;
            }
            return null;
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
            return null;
        }
    }

    public function updateStatus($order_code, $status)
    {
        try {
            $sql = "UPDATE " . $this->orders . " SET status=:status WHERE order_code=:order_code";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":order_code", $order_code);
            $stmt->bindParam(":status", $status);
            $this->connection->beginTransaction();
            if ($stmt->execute()) {
                $this->connection->commit();
                return 1000;
            } else {
                $this->connection->rollBack();
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 1001;
    }


    public function getByorder_code($order_code)
    {
        try {
            $query = "SELECT order_code,status,amount,address,shipping_fee,promotion_code,promotion_value,user_id,branch_id,promotion_id,created_at,branch_address from " . $this->orders . " WHERE order_code = :order_code";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":order_code", $order_code);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $data = array(
                    "order_code" => $order_code,
                    "status" => $status,
                    "amount" => $amount,
                    "address" => $address,
                    "shipping_fee" => $shipping_fee,
                    "promotion_code" => $promotion_code,
                    "promotion_value" => $promotion_value,
                    "user_id" => $user_id,
                    "branch_id" => $branch_id,
                    "promotion_id" => $promotion_id,
                    "created_at" => $created_at,
                    "branch_address" => $branch_address,
                );
                return $data;
            }
            return null;
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
            return null;
        }
    }
    public function insertItem($order)
    {

        try {
            if ($this->checkorder_code($order->order_code)) {
                $query = "UPDATE " . $this->orders . " SET amount = :amount,
                                                    phone = :phone,
                                                    lat = :lat,
                                                    lng = :lng,
                                                    address = :address,
                                                    branch_lat = :branch_lat,
                                                    branch_lng = :branch_lng,
                                                    branch_address = :branch_address,
                                                    shipping_fee = :shipping_fee,
                                                    promotion_code = :promotion_code,
                                                    promotion_value = :promotion_value
                                                    WHERE order_code =:order_code";
                $phone = $order->phone;
                $amount = $order->amount;
                $lat = $order->lat;
                $lng = $order->lng;
                $address = $order->address;
                $branch_lat = $order->branch_lat;
                $branch_lng = $order->branch_lng;
                $branch_address = $order->branch_address;
                $shipping_fee = $order->shipping_fee;
                $promotion_code = $order->promotion_code;
                $promotion_value = $order->promotion_value;
                $order_code = $order->order_code;

                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(":amount", $amount);
                $stmt->bindParam(":phone", $phone);
                $stmt->bindParam(":lat", $lat);
                $stmt->bindParam(":lng", $lng);
                $stmt->bindParam(":address", $address);
                $stmt->bindParam(":branch_lat", $branch_lat);
                $stmt->bindParam(":branch_lng", $branch_lng);
                $stmt->bindParam(":branch_address", $branch_address);
                $stmt->bindParam(":shipping_fee", $shipping_fee);
                $stmt->bindParam(":promotion_code", $promotion_code);
                $stmt->bindParam(":promotion_value", $promotion_value);
                $stmt->bindParam(":order_code", $order_code);
                $this->connection->beginTransaction();
                if ($stmt->execute()) {
                    $this->connection->commit();
                    return true;
                } else {
                    $this->connection->rollBack();
                    return false;
                }
            } else {
                $query = "INSERT INTO " . $this->orders . " SET order_code =:order_code,
                                                              user_id = :user_id,
                                                              branch_id = :branch_id,
                                                              promotion_id = :promotion_id";
                $order_code = $order->order_code;
                $user_id = $order->user_id;
                $branch_id = $order->branch_id;
                $stmt = $this->connection->prepare($query);
                $promotion_id = $order->promotion_id;
                $stmt->bindParam(":order_code", $order_code);
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

    public function checkorder_code($order_code)
    {
        try {
            $query = "SELECT order_code from " . $this->orders . " WHERE order_code = :order_code";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":order_code", $order_code);
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

    public function getTotalOderCountByUser($user_id)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName .
                " where status != 3 or status != 4  and user_id = :user_id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $count = $row['total'];
                $totalPage = ceil($count / $limit);
                return $totalPage;
            }
            return 0;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return 0;
        }
    }
}
