<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/category_model.php';

class CategoryService
{
    private $connection;
    private $tableName = "categories";
    private $totalPostInPage = 10;

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function getAll()
    {
        try {
            $query = "select id, name, avatar from " . $this->tableName . " where status = 0 ORDER BY id DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar,
                    );
                    array_push($data, $each);
                }
                return $data;
            }
        } catch (Exception $e) {
            //throw $th;
            echo "categories loi getAll(): " . $e->getMessage();
        }
        return null;
    }

    public function getByID($id)
    {
        try {
            $query = "select id, name, avatar from " . $this->tableName . " where id=:id and status = 0";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $data = array(
                    "id" => $id,
                    "name" => $name,
                    "avatar" => $avatar
                );

                return $data;
            }
        } catch (Exception $e) {
            echo "loi getByID(): " . $e->getMessage();
        }
        return null;
    }

    public function checkName($name)
    {
        try {
            $query = "select id from " . $this->tableName . " where name=:name and status = 0";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            echo "loi getByID(): " . $e->getMessage();
        }
        return false;
    }

    public function insertItem($category)
    {
        try {
            $query = "insert into " . $this->tableName . " set name = :name, avatar = :avatar";

            $name = $category->getName();
            $avatar = $category->getAvatar();

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":avatar", $avatar);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return false;
    }

    public function updateItem($category)
    {
        try {
            $query = "update " . $this->tableName . " set name = :name, avatar = :avatar where id = :id";

            $id = $category->getId();
            $name = $category->getName();
            $avatar = $category->getAvatar();

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":avatar", $avatar);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return false;
    }

    public function removeItem($id)
    {
        try {
            $query = "update " . $this->tableName . " set status = 1 where id = :id";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return false;
    }
}
