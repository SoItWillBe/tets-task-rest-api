<?php

namespace App\Controllers\User;

use App\Controllers\Controller;
use App\Services\User\UserService;

class UserController extends Controller
{

    private UserService $userService;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function index()
    {
        $this->jsonResponse(
            ['message' => $this->service->getAllUsers()]
        );
    }

    public function show()
    {
        $this->jsonResponse(
            ['message' => $this->service->getUserById(1)] // $request->get->id
        );
    }

    public function delete()
    {
        $this->jsonResponse(['message' => 'delete success']);
    }

}