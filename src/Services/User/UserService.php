<?php

namespace App\Services\User;

use App\Interfaces\QueryManagerInterface;

class UserService
{

    private $queryManager;

    public function __construct(QueryManagerInterface $queryManager)
    {
        $this->queryManager = $queryManager;
    }

    public function getAllUsers(): array
    {
        return $this->queryManager->all();
    }

    public function getUserById($id): array
    {
        return $this->queryManager
            ->select(['id', 'email'])
            ->where(['id' => $id])
            ->execute();
    }

    public function deleteUser($id): void
    {
        $this->queryManager
            ->delete(['id' => $id])
            ->execute();
    }
}