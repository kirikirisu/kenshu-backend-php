<?php

namespace App\Handler;

use App\Client\PostRepository;
use App\Lib\HTMLBuilder;
use App\Lib\Http\Response;

class GetTopPageHandler implements HandlerInterface
{
    public function __construct(
        public HTMLBuilder    $compose,
        public PostRepository $post_client)
    {
    }

    public function run(): Response
    {
        $post_list = $this->post_client->getPostList();

        $html = $this->compose->topPage($post_list)->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
