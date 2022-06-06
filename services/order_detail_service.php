<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/order_detail_model.php';

class OrderDetailService
{
    private $connection;
    private $order_details = "order_details";


    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }


    public function getByOrderCode($order_code) {
        try {
            $query = "select quantity, product_id, price, name from " . $this->order_details . " where order_code = :order_code";
            $stmt = $this->connection->prepare($query);
            $stmt -> bindParam(":order_code",$order_code);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "quantity" => $quantity,
                        "product_id" => $product_id,
                        "price" => $price,
                        "name" => $name
                    );
                    array_push($data, $each);
                }
                return $data;
            }
            return 1001;
        } catch (Exception $e) {
            //throw $th;
            echo "loi getAll(): " . $e->getMessage();
            return 1001;
        }
    }


    public function insertItem($orderdetail)
    {
        try {
            $query = "insert into " . $this->order_details . " set quantity = :quantity,
                                                               amount = :amount,
                                                               name = :name,
                                                               order_code = :order_code,
                                                               product_id = :product_id";

            $quantity = $orderdetail->quantity;
            $amount = $orderdetail->amount;
            $name = $orderdetail->name;
            $order_code = $orderdetail->order_code;
            $product_id = $orderdetail->product_id;

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":quantity", $quantity);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":order_code", $order_code);
            $stmt->bindParam(":product_id", $product_id);
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

}
