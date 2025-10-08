<?php

namespace App\Helpers;

class ResponseMessage
{
    public static function response(ResponseStatusesEnums $status, mixed $message, int $code = 200): array
    {
        return [
            'status' => $status,
            'message' => $message,
            'code' => $code,
        ];
    }
}