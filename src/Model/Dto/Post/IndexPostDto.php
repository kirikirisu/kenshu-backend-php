<?php
namespace App\Model\Dto\Post;

class IndexPostDto
{
    public
    function __construct(
        public int    $user_id,
        public string $title,
        public string $body)
    {
    }
}
