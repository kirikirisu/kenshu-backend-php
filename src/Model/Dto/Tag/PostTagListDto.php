<?php

namespace App\Model\Dto\Tag;

class PostTagListDto
{
    public
    function __construct(
        public int   $post_id,
        /** @var string[] $tag_list */
        public array $tag_list)
    {
    }
}
