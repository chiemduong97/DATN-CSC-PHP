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
            $query = "select * from " . $this->tableName . 
            " where code=:code and status = 1 and start <= NOW()";
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
            echo "loi getByID(): " . $e->getMessage();
            return null;
        }
       
    }

    // public function checkName($name)
    // {
    //     try {
    //         $query = "select id from " . $this->tableName . " where name=:name and status = 1";
    //         $stmt = $this->connection->prepare($query);
    //         $stmt->bindParam(":name", $name);
    //         $stmt->execute();
    //         if ($stmt->rowCount() > 0) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } catch (Exception $e) {
    //         echo "loi checkName(): " . $e->getMessage();
    //     }
    //     return false;
    // }

    // public function insertItem($branch)
    // {
    //     try {
    //         $query = "insert into " . $this->tableName . " set name = :name, location = :location";

    //         $name = $branch->getName();
    //         $location = $branch->getLocation();

    //         $stmt = $this->connection->prepare($query);
    //         $stmt->bindParam(":name", $name);
    //         $stmt->bindParam(":location", $location);
    //         $stmt->execute();
    //         if ($stmt->rowCount() > 0) {
    //             return 1000;
    //         } else {
    //             return 1001;
    //         }
    //     } catch (Exception $e) {
    //         throw $e;
    //         return 1001;
    //     }
    //     return 1001;
    // }

    // public function updateItem($branch)
    // {
    //     try {
    //         $query = "update " . $this->tableName . " 
    //         set name = :name, location = :location where id = :id and status = 1";

    //         $id = $branch->getId();
    //         $name = $branch->getName();
    //         $location = $branch->getLocation();

    //         $stmt = $this->connection->prepare($query);
    //         $stmt->bindParam(":id", $id);
    //         $stmt->bindParam(":name", $name);
    //         $stmt->bindParam(":location", $location);
    //         $stmt->execute();
    //         if ($stmt->rowCount() > 0) {
    //             return 1000;
    //         } else {
    //             return 1001;
    //         }
    //     } catch (Exception $e) {
    //         throw $e;
    //     }
    //     return 1001;
    // }

    // public function removeItem($id)
    // {
    //     try {
    //         $query = "update " . $this->tableName . " set status = 1 where id = :id";

    //         $stmt = $this->connection->prepare($query);
    //         $stmt->bindParam(":id", $id);
    //         $stmt->execute();
    //         if ($stmt->rowCount() > 0) {
    //             return 1000;
    //         } else {
    //             return 1001;
    //         }
    //     } catch (Exception $e) {
    //         throw $e;
    //     }
    //     return 1001;
    // }

    // public function checkRemove($id)
    // {
    //     try {
    //         $query = "select id from " . $this->tableName . " where id=:id and status = 1";
    //         $stmt = $this->connection->prepare($query);
    //         $stmt->bindParam(":id", $id);
    //         $stmt->execute();
    //         if ($stmt->rowCount() > 0) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } catch (Exception $e) {
    //         echo "loi checkRemove(): " . $e->getMessage();
    //     }
    //     return false;
    // }

}
