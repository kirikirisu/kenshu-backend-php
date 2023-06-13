<?php
namespace App\client;

use App\Model\Dto\IndexPostDto;
use App\Model\Dto\ShowPostDto;
use App\Model\Dto\UpdatePostDto;

interface PostClientInterface
{
    /* @return ShowPostDto[] */
    public function getPostList(): array;
    public function getPostById(int $id): ShowPostDto;
    public function createPost(IndexPostDto $payload): void;
    public function updatePost(int $post_id, UpdatePostDto $dto): void;
    public function deletePost(int $post_id): void;
}
