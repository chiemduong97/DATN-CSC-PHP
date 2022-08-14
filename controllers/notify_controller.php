<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/notify_service.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/notify_model.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/user_service.php';

class NotifyController
{
    private $service;
    public function __construct()
    {
        $this->service = new NotifyService();
    }

    public function sendNotify($action, $description, $user_id = null, $order_code = null)
    {
        $notify = new Notify();
        $notify -> action = $action;
        $notify -> description = $description;
        $notify -> order_code = $order_code;
        $notify -> user_id = $user_id;
        $userService = new UserService();
        $deviceTokens = null;
        if ($user_id == null) {
            $deviceTokens = $userService->getDeviceTokens();
        } else {
            $deviceTokens = array($userService -> getById($user_id) -> device_token);
        }
        return $this->service->sendNotify($deviceTokens, $notify);
    }

    public function getByUser($user, $page, $limit)
    {
        return $this->service->getByUser($user, $page, $limit);
    }
    public function getTotalPage($user_id, $limit)
    {
        return $this->service->getTotalPage($user_id, $limit);
    }
}
