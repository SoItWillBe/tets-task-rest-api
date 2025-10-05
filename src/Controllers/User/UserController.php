<?php

namespace App\Controllers\User;

use App\Controllers\Controller;
use App\Core\QueryManagers\UsersQueryManager;
use App\Services\User\UserService;

class UserController extends Controller
{

    private UserService $service;

    public function __construct(\PDO $pdo)
    {
        $this->service = new UserService(
            new UsersQueryManager($pdo)
        );
    }

    public function index()
    {
        $this->jsonResponse(
            ['message' => $this->service->getAllUsers()]
        );
    }

    public function show($id)
    {
        $this->jsonResponse(
            ['message' => $this->service->getUserById($id)]
        );
    }

    public function delete($id)
    {
        $this->jsonResponse(['message' => 'delete success']);
    }

}