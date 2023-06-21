<?php
namespace App\Model\Dto;

class ShowPostDto
{
    /**
     * @param int $id
     * @param int $user_id
     * @param string $title
     * @param string $body
     * @param int $thumbnail_id
     * @param string[] $tag_list
     */
    public function __construct(
        public int    $id,
        public int    $user_id,
        public string $title,
        public string $body,
        public int $thumbnail_id,
        public array $tag_list)
    {
    }
}
