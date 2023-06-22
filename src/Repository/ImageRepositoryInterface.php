<?php
namespace App\Repository;

use App\Model\Dto\IndexImageDto;

interface ImageRepositoryInterface
{
    public function insertImage(int $post_id, string $img_path): int;

    public function insertMultiImageForPost(int $post_id, array $image_list);

    public function getImageById(int $image_id): IndexImageDto;
}
