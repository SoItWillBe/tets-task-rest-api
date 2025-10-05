<?php

namespace App\Interfaces;

interface ControllerInterface {
    public function jsonResponse(array $message, int $statusCode = 200): void;
}