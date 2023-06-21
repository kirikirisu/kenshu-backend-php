<?php

namespace App\Repository;

use App\Model\Dto\IndexPostDto;
use App\Model\Dto\ShowPostDto;
use App\Model\Dto\UpdatePostDto;

interface PostRepositoryInterface
{
    /* @return ShowPostDto[] */
    public function getPostList(): array;

    public function getPostById(int $id): ShowPostDto;

    public function insertPost(IndexPostDto $payload): int;

    public function updateThumbnail(int $post_id, int $thumbnail_id): void;

    public function updatePost(int $post_id, UpdatePostDto $dto): void;

    public function deletePost(int $post_id): void;
}
