<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/statistics_service.php';

class StatisticsController
{
    private $service;
    public function __construct()
    {
        $this->service = new StatisticsService();
    }

    public function revenueByDate($start,$end)
    {
        return $this -> service -> revenueByDate($start,$end);
    }

    public function revenueByMonth()
    {
        return $this -> service -> revenueByMonth();
    }

    public function countOrder()
    {
        return $this -> service -> countOrder();
    }

    public function countOrderByDate($start,$end)
    {
        return $this -> service -> countOrderByDate($start,$end);
    }

    
}
