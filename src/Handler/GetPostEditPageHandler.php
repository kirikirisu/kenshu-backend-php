<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Response;
use App\Repository\PostRepositoryInterface;
use App\Repository\TagRepositoryInterface;

class GetPostEditPageHandler implements HandlerInterface
{
    public function __construct(
        public int                     $post_id,
        public HTMLBuilderInterface    $compose,
        public PostRepositoryInterface $post_repo,
        public TagRepositoryInterface  $tag_repo)
    {
    }

    public function run(): Response
    {
        $post = $this->post_repo->getPostById(id: $this->post_id);

        $html = $this->compose->postEditPage(post: $post)->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
