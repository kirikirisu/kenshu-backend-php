<?php

namespace App\Lib;

use App\Model\Dto\Image\IndexImageDto;
use App\Model\Dto\Post\DetailPostDto;
use App\Model\Dto\Post\ShowPostDto;
use App\Model\Dto\Tag\IndexTagDto;
use App\Model\Dto\Tag\PostTagListDto;

interface HTMLBuilderInterface
{
    /**
     * @param ShowPostDto[] $post_list
     * @param array<string, PostTagListDto> $post_tag_hash_map
     * @param string $csrf_token
     * @param array|null $error_list
     * @return $this
     */
    public function topPage(array $post_list, array $post_tag_hash_map, string $csrf_token, array $error_list = null): self;

    /**
     * @param ShowPostDto $post
     * @param IndexImageDto[] $image_list
     * @param IndexTagDto[] $tag_list
     * @return $this
     */
    public function postDetailPage(ShowPostDto $post, array $image_list, array $tag_list): self;

    /**
     * @param ShowPostDto $post
     * @param IndexImageDto[] $image_list
     * @param string $csrf_token ;
     * @param IndexTagDto[] $tag_list
     * @param int[] $checked_tag_id_list
     * @param array|null $error_list
     * @return $this
     */
    public function postEditPage(ShowPostDto $post, string $csrf_token, array $image_list, array $tag_list, array $checked_tag_id_list, array $error_list = null): self;

    public function signUpPage(string $csrf_token, ?array $error_list = null): self;

    public function signInPage(string $csrf_token): self;

    public function getHtml(): string;
}
