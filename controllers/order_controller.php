<?php

use LDAP\Result;

include_once $_SERVER['DOCUMENT_ROOT'] . '/services/order_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/order_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/order_detail_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/order_detail_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/product_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/branch_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/user_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/payment_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/transaction_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/transaction_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/notify_controller.php';


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

    public function getTotalPageByUser($limit, $user_id)
    {
        return $this->service->getTotalPageByUser($limit, $user_id);
    }

    public function destroyOrder($order_code, $status)
    {
        $order = $this->service->getByorder_code($order_code);
        if ($order["payment_method"] == "WALLET") {
            $transid = "CSCWalletRefund" . time();
            $transaction = new Transaction();
            $transactionService = new TransactionService();
            $userService = new UserService();
            $amount = ($transactionService->getTransaction($order_code))["amount"];
            $resultUser = $userService->updateWallet($amount, $order["user"]->id);
            if ($resultUser != 1000) $transaction->status = 0;
            $transaction->user_id = $order["user"]->id;
            $transaction->transid = $transid;
            $transaction->type = "refund";
            $transaction->amount = $amount;
            $transaction->order_code = $order_code;
            $resultTransaction = $transactionService->insertItem($transaction);
            if ($resultTransaction != 1000) return $resultTransaction;
        }

        if ($order["payment_method"] == "MOMO") {
            $transaction = (new TransactionService())->getTransaction($order_code);
            if (!is_null($transaction)) {
                $resultMomo = (new PaymentService())->confirmMomo($transaction["phone"], $transaction["transid"], $transaction["transid_momo"], "revertAuthorize");
                if (json_decode($resultMomo)->status == 0) {
                    $transaction["type"] = "refund";
                    $transaction["transid"] = "CSCMomoRefund" . time();
                    $transactionService = new TransactionService();
                    $resultTransaction = $transactionService->insertItem((new Transaction())->toTransaction($transaction));
                    if ($resultTransaction != 1000) return $resultTransaction;
                } else {
                    return 1018;
                }
            } else {
                return 1019;
            }
        }

        $result = $this->service->updateStatus($order_code, $status);

        if ($result == 1000) {
            (new NotifyController()) -> sendNotify(
                "ORDER_DESTROY",
                "Đơn hàng của bạn đã được hủy, mong bạn tiếp tục ủng hộ CSC ở những lần sau. CSC chờ đợi cơ hội để phục vụ bạn.",
                $this -> service -> getByorder_code($order_code)["user"] -> id,
                $order_code
            );
        }
        return $result;
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

            switch ($body->payment_method) {
                case "MOMO":
                    $paymentService = new PaymentService();
                    $partnerRefId = "CSCMomoPay" . time();
                    $resultMomo = json_decode($paymentService->payMomo($body->customerNumber, $body->appData, $body->amount, $partnerRefId));
                    if ($resultMomo->status == 0) {
                        $transaction = new Transaction();
                        $transaction->user_id = $body->user_id;
                        $transaction->transid = $partnerRefId;
                        $transaction->transid_momo = $resultMomo->transid;
                        $transaction->type = "paid";
                        $transaction->amount = $resultMomo->amount;
                        $transaction->order_code = $order->order_code;
                        $transaction->phone = $body->customerNumber;
                        $transactionService = new TransactionService();
                        $resultTransaction = $transactionService->insertItem($transaction);
                        if ($resultTransaction != 1000) return null;
                    } else {
                        return null;
                    }
                    break;
                case "WALLET":
                    $transid = "CSCWalletPay" . time();
                    $transaction = new Transaction();
                    $userService = new UserService();
                    $resultUser = $userService->updateWallet(- ($body->amount), $body->user_id);
                    if ($resultUser != 1000) $transaction->status = 0;
                    $transaction->user_id = $body->user_id;
                    $transaction->transid = $transid;
                    $transaction->type = "paid";
                    $transaction->amount = $body->amount;
                    $transactionService = new TransactionService();
                    $transaction->order_code = $order->order_code;
                    $resultTransaction = $transactionService->insertItem($transaction);
                    if ($resultTransaction != 1000) return null;
                    break;
            }


            $amount = 0;
            for ($i = 0; $i < count($order_details); $i++) {
                $quantity = $order_details[$i]->quantity;
                $product_id = $order_details[$i]->product->id;
                $price = $order_details[$i]->price;
                $orderdetail = new OrderDetail();
                $orderdetail->price = $price;
                $orderdetail->quantity = $quantity;
                $orderdetail->order_code = $order->order_code;
                $orderdetail->product_id = $product_id;
                $amount += ($price * (int)$quantity);
                $result = (new OrderDetailService)->insertItem($orderdetail);
                if (!$result) {
                    return null;
                }
            }
            $order->amount = $amount;
            $order->address = $body->address;
            $order->shipping_fee = $body->shipping_fee;
            $order->payment_method = $body->payment_method;
            $order->distance = $body->distance;
            $result = $this->service->insertItem($order);
            if ($result) {
                (new NotifyController()) -> sendNotify(
                    "ORDER_SUCCESS",
                    "Cảm ơn bạn đã cho CSC cơ hội được phục vụ. Trong vòng 15 phút nhân viên sẽ gọi điện thoại để xác nhận đơn hàng.",
                    $order->user_id,
                    $order->order_code 
                );
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

    // admin

    public function getAll($page, $limit)
    {
        return $this->service->getAll($page, $limit);
    }

    public function getTotalPage($limit)
    {
        return $this->service->getTotalPage($limit);
    }

    public function updateStatus($order_code, $status)
    {
        $result = $this->service->updateStatus($order_code, $status + 1);
        if ($result == 1000) {
            $action = "";
            $description ="";
            if ($status == 0) {
                $action = "ORDER_RECEIVED";
                $description = "Cảm ơn bạn đã cho CSC cơ hội được phục vụ. Đơn hàng đã được nhận và đang chuẩn bị, trong 15 phút sẽ bắt đầu giao đến cho bạn";
            }
            if ($status == 1) {
                $action = "ORDER_DELIVERY";
                $description = "Cảm ơn bạn đã cho CSC cơ hội được phục vụ. Đơn hàng đang được giao vui lòng đợi tài xế liên hệ khi đến nơi.";
            }
            if ($status == 2) {
                $action = "ORDER_COMPLETE";
                $description = "Cảm ơn bạn đã cho CSC cơ hội được phục vụ. CSC biết bạn có nhiều sự lựa chọn, cảm ơn vì đã chọn CSC hôm nay.";
            }
            
            (new NotifyController()) -> sendNotify(
                $action,
                $description,
                $this -> service -> getByorder_code($order_code)["user"] -> id,
                $order_code
            );
        }
        return $result;
    }
}
