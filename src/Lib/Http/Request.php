<?php
namespace App\Lib\Http;

class Request
{
    public function __construct(
        public string $method,
        public string $path,
        public mixed  $post,
        public mixed  $files
    )
    {
    }
}
