<?php
class Response
{
    public static function array($message = null)
    {
        return [
            'message' => $message
        ];
    }
}
