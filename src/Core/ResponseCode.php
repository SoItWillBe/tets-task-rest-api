<?php

namespace App\Core;

final class ResponseError
{
    public static function abort(int $code): void
    {
        http_response_code($code);
        exit();
    }
}