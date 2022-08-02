<?php
    class Transaction {
        public $transid;
        public $transid_momo;
        public $created_at;
        public $type;
        public $amount;
        public $status;
        public $user_id;
        public $order_code;
        public $phone;

        public function toTransaction($transaction) {
            $this -> transid = $transaction["transid"];
            $this -> transid_momo = $transaction["transid_momo"];
            $this -> created_at = $transaction["created_at"];
            $this -> type = $transaction["type"];
            $this -> amount = $transaction["amount"];
            $this -> user_id = $transaction["user_id"];
            $this -> order_code = $transaction["order_code"];
            $this -> phone = $transaction["phone"];
            return $this;
        }
    }

?>