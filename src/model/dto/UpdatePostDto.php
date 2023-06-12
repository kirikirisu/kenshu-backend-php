<?php
namespace App\Model\Dto;

class UpdatePostDto
{
    public function __construct(
        public string $title,
        public string $body,
        public int    $thumbnail_id)
    {
    }
}
