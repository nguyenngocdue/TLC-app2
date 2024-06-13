<?php

namespace App\Utils\System\Api;

class Response
{
    public static function array($message = null)
    {
        return [
            'message' => $message
        ];
    }
}
