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

    public function getByID($id)
    {
        try {
            $query = "select id, name, avatar, status,category_id from " . $this->tableName . " where id=:id and status = 1";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $data = array(
                    "id" => $id,
                    "name" => $name,
                    "avatar" => $avatar,
                    "status" => $status,
                    "category" => $this -> getByID($category_id)
                );

                return $data;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "loi getByID(): " . $e->getMessage();
            return null;
        }
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

            $category_id = $category -> category_id;
            $query = "";
            if (is_null($category_id)) $query = "INSERT INTO " . $this->tableName . " SET name = :name, avatar = :avatar, category_id = null";
            else $query = "INSERT INTO " . $this->tableName . " SET name = :name, avatar = :avatar, category_id = $category_id";

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

        if ($category -> id == -1) {
            return $this -> insertItem($category);
        } else {
            try {
                $category_id = $category -> category_id;
                $query = "";
                if (is_null($category_id)) $query = "UPDATE " . $this->tableName . " SET name = :name, avatar = :avatar, category_id = null WHERE id = :id";
                else $query = "UPDATE " . $this->tableName . " SET name = :name, avatar = :avatar, category_id = $category_id WHERE id = :id";
    
                $id = $category->id;
                $name = $category->name;
                $avatar = $category->avatar;
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
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
            return 1001;
        }
    }

    public function checkRemove($id)
    {
        try {
            $query = "SELECT * FROM " . $this->tableName . " WHERE id = :id AND status = 1";
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

    public function getCategoriesLevel_0()
    {
        try {
           
            $query = "select id, name, avatar from " . $this->tableName . " 
                where status = 1   and category_id IS NULL";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $data = array();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return null;
        }
    }

    public function getCategoriesLevel_1_admin()
    {
        try {
           
            $query = "select id, name, avatar from " . $this->tableName . " 
                where status = 1   and category_id IS NOT NULL";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $data = array();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return null;
        }
    }

    public function getCategoriesLevel_1($category_id = 0)
    {
        try {
           
            $query = "select id, name, avatar from " . $this->tableName . " 
                where category_id = :category_id and status = 1";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":category_id", $category_id);
            $stmt->execute();
            $data = array();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return null;
        }
    }

    public function getTotalPagesCategories()
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName . " where status = 1";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $count = $row['total'];
                $totalPage = ceil($count / $this->totalPostInPage);
                return $totalPage;
            }
            return null;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return null;
        }
    }

    public function getAll($page, $limit)
    {
        try {

            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $query = "SELECT id, name, avatar, status, created_at, category_id from " . $this->tableName . " WHERE status = 1
                     ORDER BY created_at DESC LIMIT :start, :limit";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar,
                        "status" => $status,
                        "category" => $this -> getByID($category_id)
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return null;
        }
    }



    public function getTotalPageSearch($query)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM " . $this->tableName . " WHERE status = 1 AND name like '%$query%'";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $count = $row['total'];
                $totalPage = ceil($count / $this->totalPostInPage);
                return $totalPage;
            }
            return null;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return null;
        }
    }

    public function search($query,$page, $limit)
    {
        try {

            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $sql = "SELECT id, name, avatar, status, created_at, category_id from " . $this->tableName . " WHERE status = 1
                    AND name LIKE '%$query%' ORDER BY created_at DESC LIMIT :start, :limit";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar,
                        "status" => $status,
                        "category" => $this -> getByID($category_id)
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return null;
        }
    }

}
