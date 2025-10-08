<?php

namespace App\Helpers\Auth;

class Hash
{
    public static function hashPassword($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function validate(string $hash, string $payload): bool
    {
        return password_verify($payload, $hash);
    }
}