<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/rating_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/user_service.php';

class RatingService
{
    private $connection;
    private $tableName = "ratings";

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function insertItem($body) {
        try {
            $query = "INSERT INTO " . $this->tableName . " SET rating = :rating, content = :content, user_id = :user_id, order_code = :order_code";

            $rating = $body->rating;
            $content = $body->content;
            $user_id = $body->user_id;
            $order_code = $body->order_code;

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":rating", $rating);
            $stmt->bindParam(":content", $content);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":order_code", $order_code);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return $this->connection->lastInsertId();
            } else {
                return 1001;
            }
        } catch (Exception $e) {
            throw $e;
            return 1001;
        }
    }

    public function insertImage($rating_id, $image) {
        try {
            $query = "INSERT INTO image_ratings SET rating_id = :rating_id, image = :image";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":rating_id", $rating_id);
            $stmt->bindParam(":image", $image);
            $this->connection->beginTransaction();
            if ($stmt->execute()) {
                $this->connection->commit();
                return true;
            } else {
                $this->connection->rollBack();
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return false;
    }

    public function getAll($page, $limit)
    {
        $page -= 1;
        if ($page < 0) {
            $page = 0;
        }
        $start = $page * $limit;

        try {
            $query = 
                "SELECT id,rating,content,created_at,user_id,order_code FROM " . $this->tableName . 
                " ORDER BY created_at DESC LIMIT :start , :total";
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
                        "rating" => $rating,
                        "content" => $content,
                        "created_at" => $created_at,
                        "user" => (new UserService()) -> getById($user_id),
                        "images" => $this -> getImagesByRating($id),
                        "order_code" => $order_code
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            //throw $th;
            echo "loi getRatings(): " . $e->getMessage();
            return null;
        }
    }

    public function getByUser($user_id, $page, $limit)
    {
        $page -= 1;
        if ($page < 0) {
            $page = 0;
        }
        $start = $page * $limit;

        try {
            $query = 
                "SELECT id,rating,content,created_at,user_id,order_code FROM " . $this->tableName . 
                " WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :start , :total";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(":total", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "id" => $id,
                        "rating" => $rating,
                        "content" => $content,
                        "created_at" => $created_at,
                        "user" => (new UserService()) -> getById($user_id),
                        "images" => $this -> getImagesByRating($id),
                        "order_code" => $order_code
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            //throw $th;
            echo "loi getByUser(): " . $e->getMessage();
            return null;
        }
    }

    public function getByOrderCode($order_code)
    {
        try {
            $query = 
                "SELECT id,rating,content,created_at,user_id,order_code FROM " . $this->tableName . 
                " WHERE order_code = :order_code";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":order_code", $order_code);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $data = array(
                    "id" => $id,
                    "rating" => $rating,
                    "content" => $content,
                    "created_at" => $created_at,
                    "user" => (new UserService()) -> getById($user_id),
                    "images" => $this -> getImagesByRating($id),
                    "order_code" => $order_code
                );
                return $data;
            } else {
                return null;
            }
        } catch (Exception $e) {
            //throw $th;
            echo "loi getByOrderCode(): " . $e->getMessage();
            return null;
        }
    }

    public function getImagesByRating($rating_id) {
        try {
            $query = 
                "SELECT image FROM image_ratings WHERE rating_id = :rating_id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":rating_id", $rating_id);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = $image;
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            //throw $th;
            echo "loi getImages(): " . $e->getMessage();
            return null;
        }
    }

    public function getTotalPage($limit = 10)
    {
        try {
            $query = "select COUNT(*) as total FROM " . $this->tableName;
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
