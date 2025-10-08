<?php

namespace App\Core\QueryManagers;

use App\Interfaces\QueryManagerInterface;

abstract class QueryManager implements QueryManagerInterface
{
    private string $query;

    private array $binds;

    protected function select(array $rows = null, string $table = null): self
    {
        $rows = (null === $rows) ? '*' : $rows;

        if (is_array($rows)) {
            $rows = implode(', ', $rows);
        }

        if (null === $table)
        {
            $table = $this->table;
        }

        $this->query = sprintf(
            "SELECT %s FROM %s",
            $rows, $table
        );

        return $this;
    }

    protected function where(array $where): self
    {
        $condition = 'WHERE ';
        foreach ($where as $key => $value) {
            $condition .= "$key = :$key ";
        }
        $this->query .= " {$condition}";
        $this->binds = $where;

        return $this;
    }

    protected function like(array $like): self
    {
        $condition = 'WHERE ';

        $condition .= implode(' AND ', array_map(function ($key) use ($like) {
                return "$key LIKE :$key ";
            }, array_keys($like))
        );

        $this->query .= " {$condition}";
        $this->binds = array_map(
            function ($value) {
                return "%$value%";
            }, $like
        );

        return $this;
    }

    protected function execute()
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->binds ?? []);

        return $stmt->fetchAll();
    }

    protected function insert(array $payload, string $table = null): bool
    {
        $table = $table ?? $this->table;

        $placeholders = $this->preparePlaceholders($payload);
        $this->query = "INSERT INTO {$table} ({$placeholders['key']}) VALUES ({$placeholders['value']})";

        return $this->pdo->prepare($this->query)->execute($payload);
    }

    protected function update(array $payload, array $where): bool
    {
        $setStatement = $this->setWhereStatement($where);
        $this->query = "UPDATE {$this->table} SET $setStatement";

        return $this->pdo->prepare($this->query)->execute($payload);
    }

    protected function delete(array $where): bool
    {
        $setStatement = $this->setWhereStatement($where);
        $this->query = "DELETE FROM {$this->table} WHERE {$setStatement}";

        return $this->pdo->prepare($this->query)->execute($where);
    }

    private function preparePlaceholders(array $payload): array
    {
        $keys = array_keys($payload);

        $key = implode(', ', $keys);
        $value = ":" . implode(', :', $keys);

        return ["key" => $key, "value" => $value];
    }

    private function setWhereStatement(array $payload): string
    {
        return implode(', ', array_map(function ($key) use ($payload) {
                return "$key = :$key ";
            }, array_keys($payload))
        );
    }
}