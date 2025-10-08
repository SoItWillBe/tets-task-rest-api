<?php

namespace App\Core\Container;

class UserContainer
{

    private static $id;

    public static function setUserId(int $id)
    {
        self::$id = $id;
    }

    public static function getUserId(): ?int
    {
        return self::$id ?? null;
    }
}