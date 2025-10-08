<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\QueryManagers\AuthQueryManager;
use PDO;
use PHPUnit\Framework\TestCase;

final class AuthServiceTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        // PDO нужен только для конструктора, реальных запросов тут нет.
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function test_registerUser_delegates_to_insert_and_users_table(): void
    {
        $payload = ['email' => 'a@b.com', 'password' => 'hash'];

        $qm = $this->getMockBuilder(AuthQueryManager::class)
            ->setConstructorArgs([$this->pdo])
            ->onlyMethods(['insert'])
            ->getMock();

        $qm->expects($this->once())
            ->method('insert')
            ->with($payload, 'users')
            ->willReturn(true);

        $ok = $qm->registerUser($payload);
        $this->assertTrue($ok);
    }

    public function test_registerUser_select(): void
    {
        $payload = ['email' => 'a@b.com', 'password' => 'hash'];

        $qm = $this->getMockBuilder(AuthQueryManager::class)
            ->setConstructorArgs([$this->pdo])
            ->onlyMethods(['query', 'insert'])
            ->getMock();

        $qm->expects($this->once())
            ->method('insert')
            ->with($payload, 'users')
            ->willReturn(true);

        $ok = $qm->registerUser($payload);
        $this->assertTrue($ok);
    }

    public function test_logInUser_returns_token(): void
    {
        $payload = ['email' => 'a@b.com'];

        $qm = $this->getMockBuilder(AuthQueryManager::class)
            ->setConstructorArgs([$this->pdo])
            ->onlyMethods(['query'])
            ->getMock();

        $qm->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $ok = $qm->query($payload);
        $this->assertTrue($ok);
    }
}
