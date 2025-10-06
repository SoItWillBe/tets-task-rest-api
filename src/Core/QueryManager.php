<?php

namespace App\Core;

use App\Interfaces\QueryManagerInterface;

abstract class QueryManager implements QueryManagerInterface
{
    private string $query;

    private array $binds;

    public function select(array $rows = null): self
    {
        $rows = (null === $rows) ? '*' : $rows;

        if (is_array($rows)) {
            $rows = implode(', ', $rows);
        }

        $this->query = sprintf(
            "SELECT %s FROM %s",
            $rows, $this->table
        );

        return $this;
    }

    public function where(array $where): self
    {
        $condition = 'WHERE ';
        foreach ($where as $key => $value) {
            $condition .= "$key = :$key ";
        }
        $this->query .= " {$condition}";
        $this->binds = $where;

        return $this;
    }

    public function execute()
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->binds ?? []);

        return $stmt->fetchAll();
    }

    public function insert()
    {
        //
    }

}