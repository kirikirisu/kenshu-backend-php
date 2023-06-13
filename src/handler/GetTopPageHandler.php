<?php
namespace App\Handler;

use App\Client\PostClient;
use App\Lib\PageComposer;
use App\Lib\Http\Response;

class GetTopPageHandler
{
    public function __construct(
        public PageComposer $compose,
        public PostClient $post_client)
    {
    }

    public function run(): Response
    {
        $post_list = $this->post_client->getPostList();

        $html = $this->compose->topPage($post_list)->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
