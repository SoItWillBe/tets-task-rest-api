<?php

namespace App\Services\User;

use App\Core\QueryManager;

class UserService
{

    private $queryManager;

    public function __construct(QueryManager $queryManager)
    {
        $this->queryManager = $queryManager;
    }

    public function getAllUsers(): array
    {
        return $this->queryManager
            ->select()
            ->execute();
    }

    public function getUserById($id): array
    {
        return $this->queryManager
            ->select(['id' => $id])
            ->execute();
    }

    public function deleteUser($id): void
    {
        $this->queryManager
            ->delete(['id' => $id])
            ->execute();
    }
}