<?php

namespace App\Core\QueryManagers;

class UsersQueryManager extends QueryManager
{
    protected \PDO $pdo;

    protected string $table;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->table = 'users';
    }

    public function query(array $rows = null, array $where = null): ?array
    {
        $select = $this->select($rows);
        if (null !== $where) {
            $select->where($where);
        }
        return $select->execute();
    }

    public function queryLike(array $rows = null, array $like = null): ?array
    {
        return $this->select($rows)
            ->like($like)
            ->execute();
    }

    public function create(array $payload): ?array
    {
        return $this->insert($payload) ?
            ['id' => $this->pdo->lastInsertId()] :
            null;
    }

    public function updateUser(int $id, array $payload): ?bool
    {
        return $this->update($payload, ['id' => $id]);
    }

    public function remove(int $id): bool
    {
        return $this->delete(['id' => $id]);
    }
}