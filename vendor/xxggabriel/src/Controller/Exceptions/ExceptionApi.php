<?php 

namespace App\Controller\Exceptions;

class ExceptionApi
{

    public static function setError($message, $code = null)
    {
        return [
            "error" => [
                "message" => $message,
                "code" => $code,
                "status" => false
            ]
        ];
    }

}