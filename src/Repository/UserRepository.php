<?php

namespace App\Repository;

use App\Lib\Singleton\PgConnect;

class UserRepository
{
    public function __construct(
        public ?\PDO $pdo = null)
    {
        if (is_null($pdo)) $this->pdo = PgConnect::getClient();
    }

    public function createUser()
    {
    }

}
