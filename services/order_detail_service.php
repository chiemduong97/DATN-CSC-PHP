<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/order_detail_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/product_service.php';

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
            $query = "select quantity, product_id, price from " . $this->order_details . " where order_code = :order_code";
            $stmt = $this->connection->prepare($query);
            $stmt -> bindParam(":order_code",$order_code);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "quantity" => $quantity,
                        "product" => (new ProductService()) -> getByID($product_id),
                        "price" => $price,
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            //throw $th;
            echo "loi getAll(): " . $e->getMessage();
            return null;
        }
    }


    public function insertItem($orderdetail)
    {
        try {
            $query = "insert into " . $this->order_details . " set quantity = :quantity,
                                                               price = :price,
                                                               order_code = :order_code,
                                                               product_id = :product_id";

            $quantity = $orderdetail->quantity;
            $price = $orderdetail->price;
            $order_code = $orderdetail->order_code;
            $product_id = $orderdetail->product_id;

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":quantity", $quantity);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":order_code", $order_code);
            $stmt->bindParam(":product_id", $product_id);
            $this->connection->beginTransaction();

            if ($stmt->execute()) {
                $this->connection->commit();
                return true;
            } else {
                $this->connection->rollBack();
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return false;
    }

}
