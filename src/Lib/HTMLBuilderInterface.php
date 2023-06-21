<?php
namespace App\Lib;

use App\Model\Dto\ShowPostDto;

interface HTMLBuilderInterface
{
    public function topPage(array $data_chunk, array $error_list = null): self;

    public function postDetailPage(ShowPostDto $post): self;

    public function postEditPage(ShowPostDto $post, array $error_list = null): self;

    public function getHtml(): string;
}
