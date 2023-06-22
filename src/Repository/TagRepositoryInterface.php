<?php
namespace App\Repository;

use App\Model\Dto\IndexTagDto;

interface TagRepositoryInterface {

    /**
     * @param int $post_id
     * @return IndexTagDto[]
     */
    public function getTagListByPostId(int $post_id): array;

    /**
     * @return IndexTagDto[]
     */
    public function getTagList(): array;
}
