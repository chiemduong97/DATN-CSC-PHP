<?php
class Response
{
    private $code;
    private $data;
    private $is_error;
    private $message;
    private $load_more;
    private $total;

    public function __construct($code = 400, $data = [], $load_more = false, $total = 0)
    {
        $this->code = $code;
        $this->data = $data;
        $this->is_error = true;
        $this->load_more = $load_more;
        $this->message = "Something went wrong";
        $this->total = $total;
    }

    public function response()
    {
        switch ($this->code) {
            case '1000':
                $this->is_error = false;
                $this->message = "Success";
                break;
            case '1001':
                $this->is_error = true;
                $this->message =  "Failed" ;
                break;
            case '1002':
                $this->is_error = true;
                $this->message =  "Email does not exist !";
                break;
            case '1013':
                $this->is_error = true;
                $this->message =  "Please fill out completely !";
                break;
            case '1014':
                $this->is_error = true;
                $this->message =  "Does not exist !";
                break;
            case '401':
                $this->is_error = true;
                $this->message =  "Unauthorized !";
                break;
            default:
                $this->is_error = true;
                $this->message = "Something went wrong !!";
                break;
        }

        return json_encode(array(
            "code" => $this->code,
            "is_error" => $this->is_error,
            "message" =>  $this->message,
            "load_more" =>  $this->load_more,
            "data" => $this->data,
            "total" => $this->total
        ));
    }
}
