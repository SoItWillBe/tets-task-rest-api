<?php

declare(strict_types=1);

namespace Tests\Db;

use PDO;
use PHPUnit\Framework\TestCase;

abstract class SqliteTestCase extends TestCase
{
    protected static PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new PDO('sqlite::memory:');
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Включим FK в SQLite
        self::$pdo->exec('PRAGMA foreign_keys = ON;');

        // Прогоним все SQL-файлы по порядку
        $dir = __DIR__ . '/../fixtures/sql';
        $files = glob($dir . '/*.sql');
        sort($files, SORT_NATURAL);

        foreach ($files as $file) {
            $sql = file_get_contents($file);
            // Некоторые дампы содержат несколько выражений — exec их целиком ок для SQLite
            self::$pdo->exec($sql);
        }
    }

    protected function setUp(): void
    {
        // Изоляция каждого теста: транзакция
        self::$pdo->beginTransaction();
    }

    protected function tearDown(): void
    {
        // Откат, чтобы следующий тест видел “чистое” состояние
        if (self::$pdo->inTransaction()) {
            self::$pdo->rollBack();
        }
    }
}
