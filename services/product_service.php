<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/product_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/category_service.php';


class ProductService
{
    private $connection;
    private $tableName = "products";
    // private $totalPostInPage = 10;

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function getProductsRecent($page = 1, $limit = 10, $user_id) {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $query =
                "SELECT products.id, products.name, products.avatar, products.description, 
                products.price, products.category_id, 
                ((SELECT SUM(quantity) from warehouse WHERE product_id = products.id) - 
                (CASE WHEN (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) IS NULL THEN 0 
                ELSE (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) END)) as quantity
                FROM " . $this->tableName . "
                INNER JOIN order_details ON products.id = order_details.product_id
                INNER JOIN orders ON order_details.order_code = orders.order_code
                WHERE orders.user_id =:user_id and orders.status = 3
                GROUP BY products.id 
                ORDER BY orders.created_at DESC LIMIT :start , :total";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
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
                        "category" => (new CategoryService()) -> getByID($category_id)
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

    public function getProductsHighLight($page = 1, $limit = 10) {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $query =
                "SELECT products.id, products.name, products.avatar, products.description, 
                products.price, products.category_id, 
                (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) as sold,
                ((SELECT SUM(quantity) from warehouse WHERE product_id = products.id) - 
                (CASE WHEN (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) IS NULL THEN 0 
                ELSE (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) END)) as quantity
                FROM  " . $this->tableName . "
                WHERE products.status = 1 and (SELECT sum(quantity) from order_details WHERE product_id = products.id) is not null
                ORDER BY sold DESC LIMIT :start , :total";

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
                        "sold" => $sold,
                        "price" => $price,
                        "quantity" => $quantity,
                        "category" => (new CategoryService()) -> getByID($category_id)
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

    public function getProductsNew($page = 1, $limit = 10) {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $query =
                "SELECT products.id, products.name, products.avatar, products.description, 
                products.price, products.category_id, products.created_at, products.status,
                ((SELECT SUM(quantity) from warehouse WHERE product_id = products.id) - 
                (CASE WHEN (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) IS NULL THEN 0 
                ELSE (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) END)) as quantity,
                products.status FROM  " . $this->tableName . "
                WHERE products.status = 1
                ORDER BY products.created_at DESC LIMIT :start , :total";

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
                        "created_at" => $created_at,
                        "status" => $status,
                        "category" => (new CategoryService()) -> getByID($category_id)
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

    public function getProducts($category_id = 1, $page = 1, $limit = 10)
    {
        try {
            $page -= 1;
            if ($page < 0) {
                $page = 0;
            }
            $start = $page * $limit;

            $query =
                "SELECT id, products.name, products.avatar, products.description, 
                products.price, products.category_id, 
                ((SELECT SUM(quantity) from warehouse WHERE product_id = products.id) - 
                (CASE WHEN (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) IS NULL THEN 0 
                ELSE (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) END)) as quantity,
                products.status FROM  " . $this->tableName . "
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
                        "status" => $status,
                        "category" => (new CategoryService()) -> getByID($category_id)
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
                "SELECT id, products.name, products.avatar, products.description, 
                products.price, products.category_id, products.status, products.created_at,
                ((SELECT SUM(quantity) from warehouse WHERE product_id = products.id) - 
                (CASE WHEN (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) IS NULL THEN 0 
                ELSE (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) END)) as quantity
                FROM  " . $this->tableName . "
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
                        "created_at" => $created_at,
                        "status" => $status,
                        "category" => (new CategoryService()) -> getByID($category_id)
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
            $query =
                "SELECT products.id, products.name, products.avatar, products.description, 
                products.price, products.category_id, products.status, 
                ((SELECT SUM(quantity) from warehouse WHERE product_id = products.id) - 
                (CASE WHEN (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) IS NULL THEN 0 
                ELSE (SELECT sum(OD.quantity) from order_details OD INNER JOIN orders O ON OD.order_code = O.order_code WHERE product_id = products.id AND O.status != 4) END)) as quantity
                FROM  " . $this->tableName . "
                WHERE products.id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $data = array(
                    "id" => $id,
                    "name" => $name,
                    "avatar" => $avatar,
                    "description" => $description,
                    "price" => $price,
                    "quantity" => $quantity,
                    "status" => $status,
                    "category" => (new CategoryService()) -> getByID($category_id)
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
            $query = "INSERT INTO " . $this->tableName . " SET name = :name, avatar = :avatar,
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

    public function getTotalPagesRecent($limit = 10, $user_id)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName . 
            " INNER JOIN order_details ON products.id = order_details.product_id
            INNER JOIN orders ON order_details.order_code = orders.order_code
            WHERE orders.user_id =:user_id and orders.status = 3
            GROUP BY products.id ";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
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

    public function getTotalPagesHighLight($limit = 10)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName . 
            " WHERE products.status = 1 and 
            (SELECT sum(quantity) from order_details WHERE product_id = products.id) is not null";
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

    public function getTotalPagesNew($limit = 10)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName . "
                WHERE products.status = 1 ";
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

    public function getTotalPages($category_id = 1, $limit = 10)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName . " 
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

    public function insertWarehouse($product_id,$quantity,$email)
    {
        try {
            $query = "INSERT INTO warehouse SET product_id = :product_id,
                                                quantity = :quantity,
                                                email = :email";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email);
            if ($stmt->execute() > 0) {
                return 1000;
            }
            return 1001;
        } catch (Exception $e) {
            echo "loi: " . $e->getMessage();
            return 1001;
        }
    }
}
