<?php

class Request
{
    public function __construct(
        public string $method,
        public string $path,
        public mixed  $post
    )
    {
    }
}
