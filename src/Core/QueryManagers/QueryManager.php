<?php

namespace App\Core;

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

    protected function update(array $payload, array $where = null): bool
    {
        $setStatement = implode(', ', array_map(function ($key, $value) use ($payload) {
                return "$key = '$value' ";
            }, array_keys($payload), array_values($payload))
        );

        $this->query = "UPDATE users SET $setStatement";
        if (null === $where) {
            throw new \Exception('Where cannot be null!');
        }
        $this->where($where);

        return $this->pdo->prepare($this->query)->execute($where);
    }

    protected function delete($id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    private function preparePlaceholders(array $payload): array
    {
        $keys = array_keys($payload);

        $key = implode(', ', $keys);
        $value = ":" . implode(', :', $keys);

        return ["key" => $key, "value" => $value];
    }
}