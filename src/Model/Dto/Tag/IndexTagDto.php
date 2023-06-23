<?php
namespace App\Model\Dto;

class IndexTagDto
{
    public
    function __construct(
        public int    $id,
        public string $name)
    {
    }
}
