<?php

namespace App\Repository;

use App\Lib\Singleton\PgConnect;
use App\Model\Dto\User\IndexUserDto;
use App\Model\Dto\User\DetailUserDto;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        public ?\PDO $pdo = null)
    {
        if (is_null($pdo)) $this->pdo = PgConnect::getClient();
    }

    public function insertUser(IndexUserDto $payload): int
    {
        $query = "INSERT INTO users (name, email, password, icon_url) VALUES (:name, :email, :password, :icon_url) RETURNING id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":name", $payload->name);
        $stmt->bindParam(":email", $payload->email);
        $stmt->bindParam(":password", $payload->password);
        $stmt->bindParam(":icon_url", $payload->icon_url);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['id'];
    }

    public function findUserByEmail(string $email): DetailUserDto | null
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) return null;

        return new DetailUserDto(id: $result['id'], name: $result['name'], email: $result['email'], password: $result['password'], icon_url: $result['icon_url']);
    }

}
