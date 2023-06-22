<?php

namespace App\Handler;

use App\Lib\HTMLBuilder;
use App\Lib\Http\Response;
use App\Repository\PostRepositoryInterface;

class GetTopPageHandler implements HandlerInterface
{
    public function __construct(
        public HTMLBuilder    $compose,
        public PostRepositoryInterface $post_repo)
    {
    }

    public function run(): Response
    {
        $post_list = $this->post_repo->getPostList();
        $html = $this->compose->topPage($post_list)->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
