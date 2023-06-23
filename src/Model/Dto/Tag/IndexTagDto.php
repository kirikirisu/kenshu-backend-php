<?php
namespace App\Model\Dto\Tag;

class IndexTagDto
{
    public
    function __construct(
        public int    $id,
        public string $name)
    {
    }
}
