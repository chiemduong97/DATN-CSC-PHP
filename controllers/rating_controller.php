<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/rating_service.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/models/rating_model.php';

    class RatingController{
        private $service;
        

        public function __construct(){
            $this->service = new RatingService();
        }

        public function postRating($body) {
            $rating_id = $this -> service -> insertItem($body);
            $images = $body -> images;
            for($i = 0; $i < count($images); $i++) {
                $result = $this -> service -> insertImage($rating_id, $images[$i]);
                if ($result == false) {
                    return $result;
                }
            }
            return $rating_id;
        }

        public function getRatings($page, $limit) {
            return $this -> service -> getAll($page, $limit);
        }

        public function getTotalPage($limit)
        {
            return $this->service->getTotalPage($limit);
        }
    
}
?> 