<?php

namespace App\Services\User;

use App\Core\Container\UserContainer;
use App\Core\Http\Request;
use App\Helpers\Auth\HashHelper;
use App\Helpers\ResponseStatusesEnums;
use App\Interfaces\QueryManagerInterface;

class UserService
{

    private QueryManagerInterface $queryManager;

    private Request $request;

    public function __construct(
        QueryManagerInterface $queryManager,
        Request $request
    )
    {
        $this->queryManager = $queryManager;
        $this->request = $request;
    }

    public function getAllUsers(): array
    {
        $like = (!empty($this->request->get)) ?
            $this->request->get : null;

        return ($like !== null ) ?
            $this->queryManager->queryLike(['id', 'email', 'age', 'address', 'created_at'], $like):
            $this->queryManager->query(['id', 'email', 'age', 'address', 'created_at']);
    }

    public function getUserById($id): ?array
    {
        return $this->queryManager->query(
            ['users.id', 'email', 'age', 'address', 'created_at'],
            ['id' => $id]
        );
    }

    public function updateUser($id, $request): array
    {
        if (empty($request)) {
            return [
                'status' => ResponseStatusesEnums::Error,
                'message' => 'data is invalid',
                'code' => 400
            ];
        }

        if (UserContainer::getUserId() !== $id) {
            return [
                'status' => ResponseStatusesEnums::Error,
                'message' => 'you are not allowed to edit this user',
                'code' => 401
            ];
        }

        if (isset($request['email'])) {
            $payload['email'] = $request['email'];
        }

        if (isset($request)) {
            $payload['password'] = HashHelper::hashPassword($request['password']);;
        }

        return ($this->queryManager->updateUser($id, $payload)) ?
            [
                'status' => ResponseStatusesEnums::Success,
                'message' => 'user updated successfully',
                'code' => 200
            ] : [
                'status' => ResponseStatusesEnums::Error,
                'message' => 'error while updating user',
                'code' => 500
            ];
    }

    public function deleteUser(int $id): array
    {
        if (UserContainer::getUserId() !== $id) {
            return [
                'status' => ResponseStatusesEnums::Error,
                'message' => 'you are not allowed to delete this user',
                'code' => 401
            ];
        }
        return ($this->queryManager->remove($id)) ?
            [
                'status' => ResponseStatusesEnums::Success,
                'message' => 'user deleted successfully',
                'code' => 200
            ] : [
                'status' => ResponseStatusesEnums::Error,
                'message' => 'error while deleting user',
                'code' => 500
            ];
    }
}