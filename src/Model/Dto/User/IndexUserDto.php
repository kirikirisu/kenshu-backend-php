<?php
namespace App\Model\Dto\User;

class IndexUserDto
{
    public
    function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $icon_url)
    {
    }
}
