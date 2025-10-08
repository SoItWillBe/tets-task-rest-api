<?php

namespace App\Helpers\Auth;

/**
 * Helper for password hashing and validating
 */
class HashHelper
{
    public static function hashPassword($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}