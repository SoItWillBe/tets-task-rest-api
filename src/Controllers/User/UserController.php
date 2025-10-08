<?php

namespace App\Controllers\User;

use App\Controllers\Controller;
use App\Core\Http\Request;
use App\Core\QueryManagers\UsersQueryManager;
use App\Helpers\ResponseMessage;
use App\Helpers\ResponseStatusesEnums;
use App\Services\User\UserService;

class UserController extends Controller
{

    const SUCCESS = ResponseStatusesEnums::Success;

    const ERROR = ResponseStatusesEnums::Error;

    private UserService $service;

    private Request $request;

    public function __construct(\PDO $pdo, Request $request)
    {
        parent::__construct();
        $this->service = new UserService(
            new UsersQueryManager($pdo),
            $request
        );
        $this->request = $request;
    }

    public function index()
    {
        $this->jsonResponse(
            ResponseMessage::response(self::SUCCESS, $this->service->getAllUsers())
        );
    }

    public function show($id)
    {
        // fetching user by if
        $user = $this->service->getUserById($id);

        // returns error if user not found
        $status = empty($user) ? self::ERROR : self::SUCCESS;

        // set response code depending on previous result
        $code = empty($user) ? 404 : 200;

        $this->jsonResponse(
            ResponseMessage::response($status, $user, $code)
        );
    }

    public function update($id)
    {
        $update = $this->service->updateUser($id, $this->request->json);

        $this->jsonResponse(
            ResponseMessage::response($update['status'], $update['message'], $update['code'])
        );
    }
    
    public function delete($id)
    {
        $delete = $this->service->deleteUser($id);

        $this->jsonResponse(
            ResponseMessage::response($delete['status'], $delete['message'], $delete['code'])
        );
    }

}