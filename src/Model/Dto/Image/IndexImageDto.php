<?php
namespace App\Model\Dto;

class IndexImageDto
{
    public function __construct(
        public int    $id,
        public string $post_id,
        public string $url)
    {
    }
}
