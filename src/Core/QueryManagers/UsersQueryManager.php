<?php

namespace App\Core\QueryManagers;

use App\Core\QueryManager;

class UsersQueryManager extends QueryManager
{
    protected \PDO $pdo;

    protected string $table;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->table = 'users';
    }

    public function all()
    {
        return $this->select()->execute();
    }

}