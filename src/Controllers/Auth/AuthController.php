<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Core\Http\Request;
use App\Core\QueryManagers\AuthQueryManager;
use App\Helpers\ResponseMessage;
use App\Helpers\ResponseStatusesEnums;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    private AuthService $service;

    private Request $request;

    public function __construct(\PDO $pdo, Request $request)
    {
        parent::__construct();
        $this->service = new AuthService(
            new AuthQueryManager($pdo)
        );
        $this->request = $request;
    }

    public function login()
    {
        // try to log in
        $login = $this->service->login($this->request);

        // return error if credentials doesn't match any record
        if (null === $login) {
            $this->jsonResponse(
                ResponseMessage::response(ResponseStatusesEnums::Success, 'wrong credentials', 400),
            );
        }

        // log in user
        if ($login['status'] === ResponseStatusesEnums::Success) {
            $this->jsonResponse(
                ResponseMessage::response(ResponseStatusesEnums::Success, 'login success'),
                headers: ['Authorization: Bearer ' . $login['token']]
            );
        }

        // handle other errors related to log in process
        $this->jsonResponse(
            ResponseMessage::response($login['status'], $login['message'], 400),
        );
    }

    public function register()
    {
        // try to register user
        $registrationData = $this->service->register($this->request);

        // return error if registration is not successfully with reason in message
        if (null === $registrationData) {
            $this->jsonResponse(
                ResponseMessage::response($registrationData['status'], $registrationData['message'], 400),
            );
        }

        // try to log in user if registration is successful
        $this->login();
    }

    public function logout()
    {
        $this->service->logout();

        $this->jsonResponse(
            ResponseMessage::response(ResponseStatusesEnums::Success, 'user logged out'),
        );
    }
}