<?php

namespace App\Repository;

use App\Model\Dto\Post\DetailPostDto;
use App\Model\Dto\Post\IndexPostDto;
use App\Model\Dto\Post\ShowPostDto;
use App\Model\Dto\Post\UpdatePostDto;

interface PostRepositoryInterface
{
    /* @return ShowPostDto[] */
    public function getPostList(): array;

    public function getPostById(int $id): DetailPostDto;

    public function insertPost(IndexPostDto $payload): int;

    public function updateThumbnail(int $post_id, int $thumbnail_id): void;

    public function updatePost(int $post_id, UpdatePostDto $dto): void;

    public function deletePost(int $post_id): void;
}
