<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/product_model.php';

class ProductService
{
    private $connection;
    private $tableName = "products";
    // private $totalPostInPage = 10;

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function getProducts($category_id = 1, $page = 1, $limit = 10)
    {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $query =
                "SELECT products.id, products.name, products.avatar, products.description, 
                products.price, products.category_id, warehouse.quantity
                FROM  " . $this->tableName . " INNER JOIN warehouse ON products.id = warehouse.product_id
                WHERE products.category_id = :category_id and products.status = 1 
                ORDER BY id DESC LIMIT :start , :total";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar,
                        "description" => $description,
                        "price" => $price,
                        "quantity" => $quantity,
                        "category_id" => $category_id
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            echo "loi service getProducts: " . $e->getMessage();
            return null;
        }
    }

    public function getProductsSearch($page = 1, $limit = 10, $filter)
    {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $query =
                "SELECT products.id, products.name, products.avatar, products.description, 
                products.price, products.category_id, warehouse.quantity
                FROM  " . $this->tableName . " INNER JOIN warehouse ON products.id = warehouse.product_id
                WHERE products.status = 1 and products.name like '%$filter%'
                ORDER BY id DESC LIMIT :start , :total";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar,
                        "description" => $description,
                        "price" => $price,
                        "quantity" => $quantity,
                        "category_id" => $category_id
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            echo "loi service getProducts: " . $e->getMessage();
            return null;
        }
    }

    public function getByID($id)
    {
        try {
            $query = "select id, name, avatar, description, price from " . $this->tableName . " where id=:id and status = 1";
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
                    "description" => $description,
                    "price" => $price
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

    public function insertItem($product_model, $category_id)
    {
        try {
            $query = "insert into " . $this->tableName . " set name = :name, avatar = :avatar,
             description = :description, price = :price, category_id = :category_id";

            $name = $product_model->name;
            $avatar = $product_model->avatar;
            $description = $product_model->description;
            $price = $product_model->price;

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":avatar", $avatar);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":category_id", $category_id);
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

    public function updateItem($product_model, $category_id)
    {
        try {
            $query = "update " . $this->tableName . " set name = :name, avatar = :avatar,
            description = :description, price = :price, category_id = :category_id 
            where id = :id and status = 1";

            $id = $product_model->id;
            $name = $product_model->name;
            $avatar = $product_model->avatar;
            $description = $product_model->description;
            $price = $product_model->price;

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":avatar", $avatar);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":category_id", $category_id);
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
            return 1001;
        }
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



    public function getTotalPages($category_id = 1, $limit = 10)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName . " 
                INNER JOIN warehouse ON products.id = warehouse.product_id
                WHERE products.category_id = :category_id and products.status = 1 ";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
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

    public function getTotalPagesSearch($limit = 10, $filter)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName . " 
                INNER JOIN warehouse ON products.id = warehouse.product_id
                WHERE products.status = 1 and products.name like '%$filter%' ";
            $stmt = $this->connection->prepare($query);
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
