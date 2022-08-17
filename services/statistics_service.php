<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/branch_model.php';

class StatisticsService
{
    private $connection;

    public function __construct()
    {
        $this->connection = (new DatabaseConfig())->db_connect();
    }

    public function revenueByDate($start, $end)
    {

        try {
            $query = "SELECT 
                        SUM((O.amount + O.shipping_fee - 
                            (CASE WHEN O.promotion_id IS NULL THEN 0 
                                ELSE (CASE WHEN P.value < 1 THEN P.value*O.amount 
                                    ELSE P.value END) END))) AS total,
                        DATE_FORMAT(O.created_at,'%d-%m-%Y') AS date 
                        FROM orders O 
                        LEFT JOIN promotions P 
                        ON O.promotion_id = P.id 
                        WHERE DATE(O.created_at) BETWEEN :start AND :end
                        GROUP BY date
                        ORDER BY date DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":start", $start, PDO::PARAM_STR);
            $stmt->bindParam(":end", $end, PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "total" => $total,
                        "date" => $date,
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            //throw $th;
            echo "loi get(): " . $e->getMessage();
            return null;
        }
    }

    public function revenueByMonth()
    {

        try {
            $query = "SELECT 
                        SUM((O.amount + O.shipping_fee - 
                            (CASE WHEN O.promotion_id IS NULL THEN 0 
                                ELSE (CASE WHEN P.value < 1 THEN P.value*O.amount 
                                    ELSE P.value END) END))) AS total,
                        DATE_FORMAT(O.created_at,'%Y-%m') AS month 
                        FROM orders O 
                        LEFT JOIN promotions P 
                        ON O.promotion_id = P.id 
                        GROUP BY month
                        ORDER BY month DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "total" => $total,
                        "month" => $month,
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            //throw $th;
            echo "loi get(): " . $e->getMessage();
            return null;
        }
    }

    public function countOrder()
    {

        try {
            $query = "SELECT status,COUNT(*) as quantity 
                      FROM orders GROUP BY status";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "status" => $status,
                        "quantity" => $quantity,
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            //throw $th;
            echo "loi get(): " . $e->getMessage();
            return null;
        }
    }

    public function countOrderByDate($start,$end)
    {

        try {
            $query = "SELECT DATE_FORMAT(created_at,'%d-%m-%Y') AS date, 
                      COUNT(*) as quantity,
                      (SELECT COUNT(*) FROM orders 
                      WHERE DATE_FORMAT(created_at,'%d-%m-%Y') = date AND status = 3)/
                      (SELECT COUNT(*) FROM orders 
                      WHERE DATE_FORMAT(created_at,'%d-%m-%Y') = date) as percent
                      FROM orders 
                      WHERE DATE(created_at) BETWEEN :start AND :end
                      GROUP BY date
                      ORDER BY date DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":start", $start, PDO::PARAM_STR);
            $stmt->bindParam(":end", $end, PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $each = array(
                        "date" => $date,
                        "quantity" => $quantity,
                        "percent" => $percent,
                    );
                    array_push($data, $each);
                }
            }
            return $data;
        } catch (Exception $e) {
            //throw $th;
            echo "loi get(): " . $e->getMessage();
            return null;
        }
    }
}
