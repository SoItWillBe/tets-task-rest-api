<?php

namespace App\Core;

final class ResponseCode
{
    public static function error(int $code): void
    {
        http_response_code($code);
        exit();
    }
}