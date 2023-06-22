<?php

namespace App\Handler;

use App\Lib\HTMLBuilder;
use App\Lib\Http\Response;
use App\Repository\ImageRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\TagRepositoryInterface;

class GetPostDetailPageHandler implements HandlerInterface
{
    public function __construct(
        public int                      $post_id,
        public HTMLBuilder              $compose,
        public PostRepositoryInterface  $post_repo,
        public ImageRepositoryInterface $image_repo,
        public TagRepositoryInterface   $tag_repo)
    {
    }

    public function run(): Response
    {
        $post = $this->post_repo->getPostById($this->post_id);
        $image_list = $this->image_repo->getImageListByPostId(post_id: $this->post_id);
        $tag_list = $this->tag_repo->getTagListByPostId($this->post_id);

        $html = $this->compose->postDetailPage(post: $post, image_list: $image_list, tag_list: $tag_list)->getHtml();
        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
