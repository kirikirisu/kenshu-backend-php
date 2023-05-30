<?php

class PostPayload
{
    public function __construct(
        public int    $user_id,
        public string $title,
        public string $body,
        public int    $thumbnail_id)
    {
    }
}
