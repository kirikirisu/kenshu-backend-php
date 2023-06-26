<?php
namespace App\Repository;

use App\Model\Dto\User\DetailUserDto;
use App\Model\Dto\User\IndexUserDto;

interface UserRepositoryInterface
{
    public function insertUser(IndexUserDto $payload): int;

    public function findUserByEmail(string $email): DetailUserDto | null;
}
