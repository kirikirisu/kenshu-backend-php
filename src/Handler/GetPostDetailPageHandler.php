<?php

namespace App\Handler;

use App\Lib\HTMLBuilder;
use App\Lib\Http\Response;
use App\Repository\ImageRepositoryInterface;
use App\Repository\PostRepositoryInterface;

class GetPostDetailPageHandler implements HandlerInterface
{
    public function __construct(
        public int                      $post_id,
        public HTMLBuilder              $compose,
        public PostRepositoryInterface  $post_repo,
        public ImageRepositoryInterface $image_repo)
    {
    }

    public function run(): Response
    {
        $post = $this->post_repo->getPostById($this->post_id);
        $thumbnail = $this->image_repo->getImageById(image_id: $post->thumbnail_id);

        $html = $this->compose->postDetailPage(post: $post, thumbnail: $thumbnail)->getHtml();
        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
