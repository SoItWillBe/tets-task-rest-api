<?php

namespace App\Helpers\Auth;

/**
 * Helper for auth basic check
 */

class Validate
{
    public static function auth(array $payload): bool
    {
        if (empty($payload)) {
            return false;
        }
        if (!isset($payload['email']) || empty($payload['email'])) {
            return false;
        }
        if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if (!isset($payload['password']) || empty($payload['password'])) {
            return false;
        }
        if (strlen($payload['password']) < 3) {
            return false;
        }
        return true;
    }
}