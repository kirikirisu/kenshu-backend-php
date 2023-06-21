<?php
namespace App\Model\Dto;

class StoredImageDto
{
    public function __construct(
        public array  $stored_img_uri_list,
        public string $thumbnail_uri)
    {
    }
}
