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
     * @param string[] $tag_list
     * @param string[] $image_list
     */
    public function __construct(
        public int    $id,
        public int    $user_id,
        public string $title,
        public string $body,
        public string $thumbnail_id,
        public array  $tag_list,
        public array  $image_list)
    {
    }
}
