<?php
namespace App\Repository;

use App\Model\Dto\IndexImageDto;

interface ImageRepositoryInterface
{
    public function getImageById(int $image_id): IndexImageDto;

    /**
     * @param int $post_id
     * @return IndexImageDto[]
     */
    public function getImageListByPostId(int $post_id): array;

    public function insertImage(int $post_id, string $img_path): int;

    public function insertMultiImageForPost(int $post_id, array $image_list);

}
