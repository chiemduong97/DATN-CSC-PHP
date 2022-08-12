<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/configHeader.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/authen/authen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/response_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/rating_controller.php';

(new CF_Header())->config("POST");
$body = json_decode(file_get_contents("php://input"));

$authen = new Authen();
$code = 400;
$data = null;

if ($authen->checkToken()) {
    $data = (new RatingController())->postRating($body);
    if (is_null($data)) {
        $code = 1001;
    } else {
        $code = 1000;
        $data = array(
            "rating_id" => $data
        );
    }
} else {
    $code = 401;
}

echo (
    (new Response(
        $code,
        $data,
    ))->response()
);


