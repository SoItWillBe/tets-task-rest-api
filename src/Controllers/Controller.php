<?php

namespace App\Controllers;

use App\Interfaces\ControllerInterface;

abstract class Controller implements ControllerInterface
{
    public function jsonResponse(array $message, int $statusCode = 200): void
    {
        $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo $message;
    }
}
