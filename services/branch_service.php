<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/branch_model.php';

class BranchService
{
    private $connection;
    private $tableName = "branches";

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function getAll()
    {
        try {
            $query = "select * from " . $this->tableName . 
                " where status = 1 ORDER BY id DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "lat" => $lat,
                        "lng" => $lng,
                        "address" => $address,
                    );
                    array_push($data, $each);
                }
                return $data;
            }
            return null;
        } catch (Exception $e) {
            //throw $th;
            echo "loi getAll(): " . $e->getMessage();
            return null;
        }
    }

    public function getById($id)
    {
        try {
            $query = "select id, name, lat,lng,address from " . $this->tableName . " where id=:id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $data = array(
                    "id" => $id,
                    "name" => $name,
                    "lat" => $lat,
                    "lng" => $lng,
                    "address" => $address
                );
                return $data;
            } 
            return null;
        } catch (Exception $e) {
            echo "loi getByID(): " . $e->getMessage();
            return null;
        }
       
    }

    public function checkName($name)
    {
        try {
            $query = "select id from " . $this->tableName . " where name=:name and status = 1";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "loi checkName(): " . $e->getMessage();
        }
        return false;
    }

    public function insertItem($branch)
    {
        try {
            $query = "insert into " . $this->tableName . " set name = :name, location = :location";

            $name = $branch->getName();
            $location = $branch->getLocation();

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":location", $location);
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
        return 1001;
    }

    public function updateItem($branch)
    {
        try {
            $query = "update " . $this->tableName . " 
            set name = :name, location = :location where id = :id and status = 1";

            $id = $branch->getId();
            $name = $branch->getName();
            $location = $branch->getLocation();

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":location", $location);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return 1000;
            } else {
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 1001;
    }

    public function removeItem($id)
    {
        try {
            $query = "update " . $this->tableName . " set status = 1 where id = :id";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return 1000;
            } else {
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 1001;
    }

    public function checkRemove($id)
    {
        try {
            $query = "select id from " . $this->tableName . " where id=:id and status = 1";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "loi checkRemove(): " . $e->getMessage();
        }
        return false;
    }

}
