<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/branch_model.php';

class PromotionService
{
    private $connection;
    private $tableName = "promotions";

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function getAll()
    {
        try {
            $query = "select * from " . $this->tableName . 
                " where status = 1 and start <= NOW() ORDER BY id DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "avatar" => $avatar,
                        "description" => $description,
                        "code" => $code,
                        "value" => $value,
                        "created_at" => $created_at,
                        "start" => $start,
                        "end" => $end
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

    public function getByCode($code)
    {
        try {
            $query = "SELECT * from " . $this->tableName . 
                     " WHERE code=:code";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":code", $code);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                return array(
                    "id" => $id,
                    "avatar" => $avatar,
                    "description" => $description,
                    "code" => $code,
                    "value" => $value,
                    "created_at" => $created_at,
                    "start" => $start,
                    "end" => $end
                );
            } 
            return null;
        } catch (Exception $e) {
            echo "loi getByCode(): " . $e->getMessage();
            return null;
        }
       
    }

    public function getById($id)
    {
        try {
            $query = "select * from " . $this->tableName . 
            " where id=:id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                return array(
                    "id" => $id,
                    "avatar" => $avatar,
                    "description" => $description,
                    "code" => $code,
                    "value" => $value,
                    "created_at" => $created_at,
                    "start" => $start,
                    "end" => $end
                );
            } 
            return null;
        } catch (Exception $e) {
            echo "loi getByID(): " . $e->getMessage();
            return null;
        }
       
    }

}
