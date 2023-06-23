<?php
namespace App\Lib;

use App\Model\Dto\DetailPostDto;
use App\Model\Dto\IndexImageDto;
use App\Model\Dto\IndexTagDto;

interface HTMLBuilderInterface
{
    public function topPage(array $data_chunk, array $error_list = null): self;

    /**
     * @param DetailPostDto $post
     * @param IndexImageDto[] $image_list
     * @param IndexTagDto[] $tag_list
     * @return $this
     */
    public function postDetailPage(DetailPostDto $post, array $image_list, array $tag_list): self;

    /**
     * @param DetailPostDto $post
     * @param IndexImageDto[] $image_list
     * @param IndexTagDto[] $tag_list
     * @param int[] $checked_tag_id_list
     * @param array|null $error_list
     * @return $this
     */
    public function postEditPage(DetailPostDto $post, array $image_list, array $tag_list, array $checked_tag_id_list, array $error_list = null): self;

    public function getHtml(): string;
}
