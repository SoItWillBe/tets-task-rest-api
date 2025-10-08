<?php

namespace App\Services\Auth;

use App\Core\Container\UserContainer;
use App\Core\Http\Request;
use App\Helpers\Auth\HashHelper;
use App\Helpers\Auth\Validate;
use App\Helpers\ResponseMessage;
use App\Helpers\ResponseStatusesEnums;
use App\Interfaces\QueryManagerInterface;

class AuthService {

    const SUCCESS = ResponseStatusesEnums::Success;
    const ERROR = ResponseStatusesEnums::Error;

    private $queryManager;

    public function __construct(QueryManagerInterface $queryManager)
    {
        $this->queryManager = $queryManager;
    }

    public function login(Request $request): ?array
    {
        // returns error if users data not valid
        if (false === Validate::auth($request->post))
        {
            return ResponseMessage::response(self::ERROR, 'Wrong credentials');
        }

        $payload['email'] = $request->post['email'];
        // finding user
        $user = $this->queryManager->query(['id', 'email', 'password'], ['email' => $payload['email']], 'users');

        // returns error if user not exists
        if (null === $user)
        {
            return ResponseMessage::response(self::ERROR, 'Wrong credentials', 400);
        }

        // returns error if passwords doesnt match
        if (empty($user) || false === password_verify($request->post['password'], $user[0]['password']))
        {
            return ResponseMessage::response(self::ERROR, 'Wrong credentials', 400);
        }

        // generating session token
        $token = $this->generateToken();

        // registering session token
        $this->queryManager->storeSessionToken($user[0]['id'], $token);

        return ['token' => $token, 'status' => self::SUCCESS, 'message' => $user[0]['id']];
    }

    public function register(Request $request): ?array
    {
        // returns error if auth data is not valid
        if (false === Validate::auth($request->post)) {
            return ResponseMessage::response(self::ERROR, 'Wrong credentials', 400);
        }
        $payload['email'] = $request->post['email'];

        // finding user
        $user = $this->queryManager->query(['email'], ['email' => $payload['email']], 'users');

        // returns error if user not exists
        if (!empty($user)) {
            return ResponseMessage::response(self::SUCCESS, 'User already exists.');
        }

        // hashing password
        $payload['password'] = HashHelper::hashPassword($request->post['password']);

        // registering user
        $register = $this->queryManager->registerUser($payload);

        // returns new user id or error
        return ($register) ?
            ResponseMessage::response(self::SUCCESS, 'user created successfully.') :
            ResponseMessage::response(self::ERROR, 'error while registering user', 500);
    }

    public function logout()
    {
        $this->queryManager->killSession(UserContainer::getUserId());
    }

    private function generateToken(): string
    {
        // generating random string as token
        return bin2hex(random_bytes(16) . time());
    }

}