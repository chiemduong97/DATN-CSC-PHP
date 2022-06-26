<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/order_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/order_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/order_detail_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/order_detail_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/product_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/branch_service.php';

class OrderController
{
    private $service;


    public function __construct()
    {
        $this->service = new OrderSerivce();
    }

    public function getByUser($user_id, $page, $limit)
    {
        return $this->service->getByUser($user_id, $page, $limit);
    }

    public function getTotalPages($limit)
    {
        return $this->service->getTotalPages($limit);
    }

    public function updateStatus($order_code, $status)
    {
        return $this->service->updateStatus($order_code, $status);
    }

    public function insertItem($body)
    {
        $order_details = $body->order_details;
        $order = new Order();
        $order->order_code = strtoupper(substr(md5(microtime()), rand(0, 26), 6));
        $order->user_id = $body->user_id;
        $order->branch_id = $body->branch_id;
        $order->promotion_id = isset($body->promotion_id) ? $body->promotion_id : null;
        $result = $this->service->insertItem($order);
        if ($result) {
            $amount = 0;
            for ($i = 0; $i < count($order_details); $i++) {
                $quantity = $order_details[$i]->quantity;
                $product_id = $order_details[$i]->product_id;
                $price = $order_details[$i]->price;
                $orderdetail = new OrderDetail();
                $orderdetail->price = $price;
                $orderdetail->quantity = $quantity;
                $orderdetail->order_code = $order->order_code;
                $orderdetail->product_id = $product_id;
                $orderdetail->name = $order_details[$i]->name;
                $amount += ($price * (int)$quantity);
                $result = (new OrderDetailService)->insertItem($orderdetail);
                if (!$result) {
                    return null;
                }
            }
            $order->amount = $amount;
            $order->lat = $body->lat;
            $order->lng = $body->lng;
            $order->address = $body->address;
            $order->phone = $body->phone;
            $order->shipping_fee = $body->shipping_fee;
            $order->promotion_code = isset($body->promotion_code) ? $body->promotion_code : null;
            $order->promotion_value = isset($body->promotion_value) ? $body->promotion_value : null;
            $order->branch_lat = $body->branch_lat;
            $order->branch_lng = $body->branch_lng;
            $order->branch_address = $body->branch_address;
            $result = $this->service->insertItem($order);
            if ($result) {
                return $order->order_code;
            }
        }
        return null;
    }

    public function getByorder_code($order_code)
    {
        return $this->service->getByorder_code($order_code);
    }
    public function getTotalOderCountByUser($user_id)
    {
        return $this->service->getTotalOderCountByUser($user_id);
    }
}
