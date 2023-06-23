<?php
namespace App\Repository;

use App\Model\Dto\Tag\IndexTagDto;

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

    public function insertMultiTag(int $post_id, array $tag_list): void;
}
