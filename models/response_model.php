<?php
class Response
{
    private $code;
    private $data;
    private $isError;
    private $message;

    public function __construct($code = 400, $data = [], $isError = true, $message = " Failed Response __construct")
    {
        $this->code = $code;
        $this->data = $data;
        $this->isError = $isError;
        $this->message = $message;
    }

    public function response()
    {
        return json_encode( array(
            "code" => $this->code,
            "data" => $this->data,
            "isError" => $this->isError,
            "message" => $this->message
        ));
    }
}
