<?php

namespace App\Controllers;

use App\Core\Http\Request;
use App\Core\QueryManagers\UsersQueryManager;
use App\Interfaces\ControllerInterface;
use App\Services\User\UserService;

/**
 * Main controller. Unifies response.
 */
abstract class Controller implements ControllerInterface
{

    public function __construct()
    {}

    public function jsonResponse(array $message, int $statusCode = 200, array $headers = null): void
    {
        $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        if (!empty($headers)) {
            foreach ($headers as $header)
            {
                header($header);
            }
        }
        http_response_code($statusCode);
        echo $message;
    }
}
