<?php

namespace App\Core;

use App\Interfaces\DatabaseConnectionInterface;

class Database implements DatabaseConnectionInterface
{
    private \PDO $connection;

    public function getConnection(): \PDO
    {
        return $this->connection;
    }

    public function setConnection(array $config): \PDO
    {
        $this->connection = new \PDO(
            "{$config['driver']}:host={$config['host']};dbname={$config['db_name']};charset={$config['charset']}",
            $config['user'],
            $config['pass'],
            $config['options']
        );

        if ($this->connection->errorCode() !== '00000') {
            throw new \Exception(
                "Connection failed: " . implode(' ', $this->connection->errorInfo())
            );
        }

        return $this->connection;
    }

}