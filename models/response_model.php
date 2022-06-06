<?php
class Response
{
    private $code;
    private $data;
    private $isError;
    private $message;

    public function __construct($code = 400, $data = [])
    {
        $this->code = $code;
        $this->data = $data;
        $this->isError = true;
        $this->message = "Something went wrong";
    }

    public function response()
    {
        switch ($this->code) {
            case '1000':
                $this->isError = false;
                $this->message = "Success";
                break;
            case '1001':
                $this->isError = true;
                $this->message =  "Failed by data = " . $this->data;
                break;
            case '1002':
                $this->isError = true;
                $this->message =  "Email does not exist !";
                break;
            case '1013':
                $this->isError = true;
                $this->message =  "Please fill out completely !";
                break;
            case '1014':
                $this->isError = true;
                $this->message =  "Does not exist !";
                break;
            case '401':
                $this->isError = true;
                $this->message =  "Unauthorized !";
                break;
            default:
                $this->isError = true;
                $this->message = "Something went wrong !!";
                break;
        }

        return json_encode(array(
            "code" => $this->code,
            "data" => $this->data,
            "isError" => $this->isError,
            "message" =>  $this->message
        ));
    }
}
