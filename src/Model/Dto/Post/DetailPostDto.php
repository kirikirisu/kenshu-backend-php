<?php

namespace App\Model\Dto;

class DetailPostDto
{
    /**
     * @param int $id
     * @param int $user_id
     * @param string $title
     * @param string $body
     * @param string $thumbnail_id
     * @param string $thumbnail_url
     */
    public function __construct(
        public int    $id,
        public int    $user_id,
        public string $title,
        public string $body,
        public string $thumbnail_id,
        public string $thumbnail_url)
    {
    }
}
