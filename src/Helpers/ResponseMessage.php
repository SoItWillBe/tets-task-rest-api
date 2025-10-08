<?php

namespace App\Helpers;

class ResponseMessage
{
    public static function response(ResponseStatusesEnums $status, mixed $message): array
    {
        return [
            'status' => $status,
            'message' => $message,
        ];
    }
}