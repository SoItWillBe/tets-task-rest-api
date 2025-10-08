<?php

namespace App\Services\User;

use App\Core\Container\UserContainer;
use App\Core\Http\Request;
use App\Helpers\Auth\HashHelper;
use App\Helpers\ResponseMessage;
use App\Helpers\ResponseStatusesEnums;
use App\Interfaces\QueryManagerInterface;

class UserService
{

    const ERROR = ResponseStatusesEnums::Error;

    const SUCCESS = ResponseStatusesEnums::Success;

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
        // "like" needs to be used if you want to use GET params as filter
        $like = (!empty($this->request->get)) ?
            $this->request->get : null;

        // collection defined to be sure you fetch similar data collection
        $collection = ['id', 'email', 'age', 'address', 'created_at'];

        // queryLike used if you have any filter via GET param. Otherwise, it fetches all.
        return ($like !== null ) ?
            $this->queryManager->queryLike($collection, $like):
            $this->queryManager->query($collection);
    }

    public function getUserById($id): ?array
    {
        // We don't fetch users password
        return $this->queryManager->query(
            ['users.id', 'email', 'age', 'address', 'created_at'],
            ['id' => $id]
        );
    }

    public function updateUser($id, $request): array
    {
        if (empty($request)) {
            return ResponseMessage::response(self::ERROR, 'data is invalid', 400);
        }

        // denies update from not owner
        if (UserContainer::getUserId() !== $id) {
            return ResponseMessage::response(self::ERROR, 'you are not allowed to edit this user', 401);
        }

        if (isset($request['email'])) {
            $payload['email'] = $request['email'];
        }

        if (isset($request)) {
            $payload['password'] = HashHelper::hashPassword($request['password']);;
        }

        // preventing empty action
        if (empty($payload)) {
            return ResponseMessage::response(
                self::ERROR,
                'no data on update',
                400
            );
        }

        return ($this->queryManager->updateUser($id, $payload)) ?
            ResponseMessage::response(self::SUCCESS, 'user updated successfully') :
            ResponseMessage::response(self::ERROR, 'error while updating user', 500);
    }

    public function deleteUser(int $id): array
    {
        // denies delete by not owner
        if (UserContainer::getUserId() !== $id) {
            return ResponseMessage::response(self::ERROR, 'you are not allowed to delete this user', 401);
        }

        return ($this->queryManager->remove($id)) ?
            ResponseMessage::response(self::SUCCESS, 'user deleted successfully') :
            ResponseMessage::response(self::SUCCESS, 'error while deleting user', 500);
    }
}