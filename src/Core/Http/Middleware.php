<?php

namespace App\Core\Http;

final class Middleware
{
    public static function checkAuth(bool $handler, array $headers, \PDO $pdo): ?int
    {
        // auth is not provided for handler, so nothing to check.
        if (false === $handler)
        {
            return null;
        }

        // return false if auth needed but header is not found
        if (!isset($headers['Authorization']))
        {
            return -1;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        $stmt = $pdo->prepare("SELECT id FROM users_token WHERE token = :token");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();

        if (false !== $user)
        {
            return $user['id'];
        }

        return -1;
    }
}