<?php

namespace App\Exceptions;

use Exception;
use App\Commons\ErrorCode;

class PosException extends Exception
{
    public $code;
    public $messageCode;
    public $functionCode;
    public $message;
    public $statusCode = 500;

    public function __construct($functionCode, $messageCode, $statusCode = null, $message = null)
    {
        $this->functionCode = $functionCode;
        $this->messageCode = $messageCode;
        $this->message = $message;
        if ($statusCode) {
            $this->statusCode = $statusCode;
        }
    }

    public function build()
    {
        if($this->functionCode && $this->messageCode){
            try {
                $this->message = ErrorCode::_[$this->functionCode][$this->messageCode];
            } catch (\Throwable $th) {
                $this->message = 'Undefined messages.';
            }
            $this->code = "A-$this->functionCode-$this->messageCode";
        }
    }
}
