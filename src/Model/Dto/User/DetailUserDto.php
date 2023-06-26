<?php
namespace App\Model\Dto\User;

class DetailUserDto
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $email,
        public string $password,
        public string $icon_url)
    {
    }
}
