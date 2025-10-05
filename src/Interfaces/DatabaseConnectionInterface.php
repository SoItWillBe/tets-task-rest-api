<?php

namespace App\Interfaces;

interface DatabaseConnectionInterface {
    public function getConnection(): \PDO;
}