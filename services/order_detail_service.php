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


    public function insertItem($orderdetail)
    {
        try {
            $query = "insert into " . $this->order_details . " set quantity = :quantity,
                                                               amount = :amount,
                                                               ordercode = :ordercode,
                                                               product_id = :product_id";

            $quantity = $orderdetail->quantity;
            $amount = $orderdetail->amount;
            $ordercode = $orderdetail->ordercode;
            $product_id = $orderdetail->product_id;

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":quantity", $quantity);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":ordercode", $ordercode);
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
