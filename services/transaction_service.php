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
}


