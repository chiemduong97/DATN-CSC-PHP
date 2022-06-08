<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/category_model.php';

class CategoryService
{
    private $connection;
    private $tableName = "categories";
    // private $totalPostInPage = 10;

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function getByID($id)
    {
        try {
            $query = "select id, name, avatar from " . $this->tableName . " where id=:id and status = 1";
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
            } else {
                return 1001;
            }
        } catch (Exception $e) {
            echo "loi getByID(): " . $e->getMessage();
            return 1001;
        }
        return 1001;
    }

    public function checkName($id = 0, $name)
    {
        try {
            $query = "select id from " . $this->tableName . " where name=:name and status = 1 and id <> :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
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

    public function insertItem($category)
    {
        try {
            $query = "insert into " . $this->tableName . " set name = :name, avatar = :avatar";

            $name = $category->name;
            $avatar = $category->avatar;

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":avatar", $avatar);
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

    public function updateItem($category)
    {
        try {
            $query = "update " . $this->tableName . " 
            set name = :name, avatar = :avatar where id = :id and status = 1";

            $id = $category->id;
            $name = $category->name;
            $avatar = $category->avatar;

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":avatar", $avatar);
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

    public function removeItem($id)
    {
        try {
            $query = "update " . $this->tableName . " set status = 0 where id = :id";

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
            echo "loi getByID(): " . $e->getMessage();
        }
        return false;
    }

    public function getCategoriesLevel_0($page = 1, $limit = 10)
    {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;
            $query = "select id, name, avatar from " . $this->tableName . " 
                where status = 1   and category_id IS NULL ORDER BY id DESC LIMIT :start , :total";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar
                    );
                    array_push($data, $each);
                }
                return $data;
            }
            return [];
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return [];
        }
    }

    public function getCategoriesLevel_1($page = 1, $limit = 10, $category_id)
    {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;
            $query = "select id, name, avatar from " . $this->tableName . " 
                where status = 1  AND category_id = :category_id 
                ORDER BY id DESC LIMIT :start , :total";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar
                    );
                    array_push($data, $each);
                }
                return $data;
            }
            return [];
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return [];
        }
    }

    public function getTotalPagesCategories()
    {
        try {
            $query = "select COUNT(id) as total FROM " . $this->tableName . " where status = 1";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $count = $row['total'];
                $totalPage = ceil($count / $this->totalPostInPage);
                return $totalPage;
            }
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
        }
        return null;
    }
}
