<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/order_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/branch_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/rating_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/order_detail_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/user_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/promotion_service.php';


class OrderSerivce
{
    private $connection;
    private $orders = "orders";


    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }
    public function getTotalPageByUser($limit = 10, $user_id)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->orders . " WHERE user_id = :user_id";
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
            $status = "";
            if ($limit == 1) $status = " status != 3 and status != 4 and ";
            $query = "SELECT order_code,status,amount,address,
            shipping_fee,payment_method,user_id,branch_id,
            promotion_id,created_at,distance from " . $this->orders . " 
            WHERE " . $status . " user_id = :user_id ORDER BY created_at DESC LIMIT :start , :total";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "order_code" => $order_code,
                        "status" => $status,
                        "amount" => $amount,
                        "address" => $address,
                        "shipping_fee" => $shipping_fee,
                        "created_at" => $created_at,
                        "payment_method" => $payment_method,
                        "distance" => $distance,
                        "promotion" => (new PromotionService()) -> getById($promotion_id),
                        "user" => (new UserService()) -> getById($user_id),
                        "branch" => (new BranchService()) -> getById($branch_id),
                        "rating" => (new RatingService()) -> getByOrderCode($order_code),
                        "order_details" => (new OrderDetailService()) -> getByOrderCode($order_code)
                    );
                    array_push($data, $each);
                }
            }
            return $data;
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
            $stmt->bindParam(":status", $status, PDO::PARAM_INT);
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
            $query = "SELECT order_code,status,amount,address,shipping_fee,user_id,branch_id,promotion_id,payment_method,created_at,distance from " . $this->orders . " WHERE order_code = :order_code";
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
                    "created_at" => $created_at,
                    "payment_method" => $payment_method,
                    "distance" => $distance,
                    "promotion" => (new PromotionService()) -> getById($promotion_id),
                    "user" => (new UserService()) -> getById($user_id),
                    "branch" => (new BranchService()) -> getById($branch_id),
                    "rating" => (new RatingService()) -> getByOrderCode($order_code),
                    "order_details" => (new OrderDetailService()) -> getByOrderCode($order_code)
                );
                return $data;
            }
            return 1021;
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
                                                    address = :address,
                                                    shipping_fee = :shipping_fee,
                                                    payment_method = :payment_method,
                                                    distance = :distance
                                                    WHERE order_code = :order_code";
                $amount = $order->amount;
                $address = $order->address;
                $shipping_fee = $order->shipping_fee;
                $payment_method = $order->payment_method;
                $distance = $order->distance;
                $order_code = $order->order_code;

                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(":amount", $amount);
                $stmt->bindParam(":address", $address);
                $stmt->bindParam(":shipping_fee", $shipping_fee);
                $stmt->bindParam(":payment_method", $payment_method);
                $stmt->bindParam(":distance", $distance);
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
            $query = "select COUNT(*) as total FROM " . $this->orders .
                " where status != 3 and status != 4  and user_id = :user_id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $count = $row['total'];
                return $count;
            }
            return 0;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return 0;
        }
    }

    //admin
    public function getTotalPage($limit = 10)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->orders;
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
    public function getAll($page, $limit)
    {
        try {
            $page -= 1;
            $page < 0 ? $page = 0 : $page;
            $start = $page * $limit;
            $status = "";
            $query = "SELECT order_code,status,amount,address,
            shipping_fee,payment_method,user_id,branch_id,
            promotion_id,created_at,distance from " . $this->orders . " 
            ORDER BY created_at DESC LIMIT :start , :total";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "order_code" => $order_code,
                        "status" => $status,
                        "amount" => $amount,
                        "address" => $address,
                        "shipping_fee" => $shipping_fee,
                        "created_at" => $created_at,
                        "payment_method" => $payment_method,
                        "distance" => $distance,
                        "promotion" => (new PromotionService()) -> getById($promotion_id),
                        "user" => (new UserService()) -> getById($user_id),
                        "branch" => (new BranchService()) -> getById($branch_id),
                        "rating" => (new RatingService()) -> getByOrderCode($order_code),
                        "order_details" => (new OrderDetailService()) -> getByOrderCode($order_code)
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
            return null;
        }
    }
}
