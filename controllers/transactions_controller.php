<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/transaction_service.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/payment_service.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/user_service.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/models/transaction_model.php';

    class TransactionController{
        private $service;
        
        public function __construct(){
            $this->service = new TransactionService();
        }

        public function createRecharge($body) {
            $paymentService = new PaymentService();
            $partnerRefId = "CSCMomoPay" . time();
            $result = json_decode($paymentService -> payMomo($body -> customerNumber, $body -> appData, $body -> amount, $partnerRefId));
            if ($result -> status == 0) {
                $transaction = new Transaction();
                $userService = new UserService();
                $resultUser = $userService -> updateWallet($result -> amount, $body -> user_id);
                if ($resultUser != 1000) $transaction -> status = 0;
                $transaction -> user_id = $body -> user_id;
                $transaction -> transid = $partnerRefId;
                $transaction -> transid_momo = $result -> transid;
                $transaction -> type = "recharge";
                $transaction -> amount = $result -> amount;
                $transaction -> phone = $body -> customerNumber;
                return $this -> service -> insertItem($transaction);
            } 
            return 1016;
        }

        public function getTransaction($type, $user_id, $page, $limit) {
            return $this -> service -> getTransactions($type, $user_id, $page, $limit);
        }

        public function getTotalPages($type, $user_id, $limit)
        {
            return $this->service->getTotalPages($type, $user_id, $limit);
        }
    }
?> 