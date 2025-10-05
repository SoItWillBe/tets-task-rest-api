<?php

namespace App\Core;

use MongoDB\Driver\Query;
use PDO;

class QueryManager
{

    private \PDO $pdo;

    private string $query;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function select(array $range = null): QueryManager
    {
        $condition = (null === $range) ? '*' : $range;

        return $this;
    }

//    public function insert()
//    {
//        //
//    }


    public function execute()
    {
//        return $this->pdo->exec($this->query);
    }

}