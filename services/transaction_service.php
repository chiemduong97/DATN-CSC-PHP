<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/transaction_model.php';

class TransactionService
{
    private $connection;
    private $tableName = "transactions";

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function insertItem($transaction)
    {
        try {
            $query = "insert into " . $this->tableName . " set transid = :transid, transid_momo =:transid_momo, type = :type,
             amount = :amount, user_id = :user_id, order_code = :order_code";

            $transid = $transaction->transid;
            $transid_momo = $transaction->transid_momo;
            $type = $transaction->type;
            $amount = $transaction->amount;
            $user_id = $transaction->user_id;
            $order_code = $transaction->order_code;

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":transid", $transid);
            $stmt->bindParam(":transid_momo", $transid_momo);
            $stmt->bindParam(":type", $type);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":order_code", $order_code);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return 1000;
            } else {
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
            return 1001;
        }
    }

    public function getTransactions($type, $user_id, $page = 1, $limit = 10)
    {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $type_string = ($type == "RECHARGE") ? " = 'recharge' " : " != 'recharge' AND transid_momo is null ";

            $query =
                "SELECT transid, transid_momo, created_at, type, 
                amount, status, order_code, user_id
                FROM  " . $this->tableName . " WHERE type $type_string AND user_id = :user_id
                ORDER BY created_at DESC LIMIT :start , :total";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "transid" => $transid,
                        "transid_momo" => $transid_momo,
                        "created_at" => $created_at,
                        "type" => $type,
                        "amount" => $amount,
                        "status" => $status,
                        "order_code" => $order_code,
                        "user_id" => $user_id
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            echo "loi service getTransactions: " . $e->getMessage();
            return null;
        }
    }

    public function getTotalPages($type, $user_id, $limit = 10)
    {
        try {
            $type_string = ($type == "RECHARGE") ? " = 'recharge' " : " != 'recharge' AND transid_momo is null ";
            $query =
                "SELECT COUNT(*) as total FROM  " . $this->tableName . " 
                WHERE type $type_string AND user_id = :user_id ";
            $stmt = $this->connection->prepare($query);
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
}


