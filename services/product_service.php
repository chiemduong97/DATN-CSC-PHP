<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/product_model.php';

class ProductService
{
    private $connection;
    private $tableName = "products";
    private $totalPostInPage = 10;

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function getByCategory($category_id,$branch_id)
    {
        try {
            $query = "SELECT products.id,
                             products.name, 
                             products.avatar, 
                             products.price, 
                             products.description, 
                             products.createdAt, 
                             products.updatedAt, 
                             products.category_id,
                             quantities.quantity
                             FROM " . $this->tableName . 
                             " INNER JOIN quantities ON products.id = quantities.product_id
                             WHERE products.category_id = :category_id AND quantities.branch_id = :branch_id 
                             AND products.status = 1 ORDER BY products.createdAt DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":category_id", $category_id);
            $stmt->bindParam(":branch_id", $branch_id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar,
                        "price" => $price,
                        "description" => $description,
                        "createdAt" => $createdAt,
                        "updatedAt" => $updatedAt,
                        "category_id" => $category_id,
                        "quantity" => $quantity
                    );
                    array_push($data, $each);
                }
                return $data;
            }
            return array();
        } catch (Exception $e) {
            //throw $th;
            echo "products loi getAll(): " . $e->getMessage();
            return null;
        }
        return null;
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
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "loi getByID(): " . $e->getMessage();
        }
        return false;
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

    public function insertItem($product_model,$category_id)
    {
        try {
            $query = "insert into " . $this->tableName . " set name = :name, avatar = :avatar,
             description = :description, price = :price, category_id = :category_id";

            $name = $product_model->name;
            $avatar = $product_model->avtar;
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
        }
        return 1001;
    }

    public function updateItem($product_model,$category_id)
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
        }
        return 1001;
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

    public function getProductsWithPageByCategoryAndBranch($category_id = 1, $branch_id = 1,$page = 1)
    {
        try {
            $page -= 1;
            $start = $page * $this->totalPostInPage;

            $query = 
            "SELECT products.id, products.name, products.avatar, products.description, 
                products.price, quantities.quantity
                FROM  " . $this->tableName . " INNER JOIN quantities ON products.id = quantities.product_id
                WHERE products.category_id = :category_id and quantities.branch_id = :branch_id and products.status = 1 
                ORDER BY id DESC LIMIT :start , :total";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $this->totalPostInPage, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "name" => $name,
                        "avatar" => $avatar,
                        "description" => $description,
                        "price" => $price,
                        "quantity" => $quantity
                    );
                    array_push($data, $each);
                }
                return $data;
            }
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
        }
        return null;
    }

    public function getTotalPages($category_id = 1, $branch_id = 1)
    {
        try {
            $query = "select COUNT(id) as total FROM " . $this->tableName ." 
                INNER JOIN quantities ON products.id = quantities.product_id
                WHERE products.category_id = :category_id and quantities.branch_id = :branch_id and products.status = 1 ";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT); 
            $stmt->bindParam(':branch_id', $branch_id, PDO::PARAM_INT); 
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
?>