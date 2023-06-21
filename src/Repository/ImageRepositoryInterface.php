<?php
namespace App\Repository;

interface ImageRepositoryInterface
{
    public function insertImage(int $post_id, string $img_path): int;

    public function insertMultiImageForPost(int $post_id, array $image_list);
}
