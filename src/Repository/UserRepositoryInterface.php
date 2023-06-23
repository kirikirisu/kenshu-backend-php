<?php
namespace App\Repository;

use App\Model\Dto\User\IndexUserDto;

interface UserRepositoryInterface
{
    public function insertUser(IndexUserDto $payload): int;
}
