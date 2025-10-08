<?php

namespace Unit;

use App\Core\Container\UserContainer;
use App\Core\QueryManagers\QueryManager;
use App\Services\User\UserService;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    protected function setUp(): void
    {
        // Мок стандартным API PHPUnit
        $this->repo = $this->createMock(UserRepository::class);
        $this->svc  = new UserService($this->repo);
    }



}
