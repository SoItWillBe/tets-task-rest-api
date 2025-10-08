<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Core\QueryManagers\AuthQueryManager;
use Tests\Db\SqliteTestCase;

final class AuthQueryManagerSqliteTest extends SqliteTestCase
{
    public function test_storeSessionToken_inserts_and_replaces_active(): void
    {
        $qm = new AuthQueryManager(self::$pdo);

        // данные уже есть из 03_seed.sql, если ты их туда положил
        $ok = $qm->storeSessionToken(5, 'new-token');
        $this->assertTrue($ok);

        $row = $qm->getSessionToken('new-token');
        $this->assertNotEmpty($row);
    }
}
