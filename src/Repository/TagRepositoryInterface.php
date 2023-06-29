<?php
namespace App\Repository;

use App\Model\Dto\Tag\IndexTagDto;
use App\Model\Dto\Tag\PostTagListDto;

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

    /**
     * @param int[] $post_id_list
     * @return PostTagListDto[]
     */
    public function getPostTagByPostIdList(array $post_id_list): array;

    public function insertMultiTag(int $post_id, array $tag_list): void;
}
