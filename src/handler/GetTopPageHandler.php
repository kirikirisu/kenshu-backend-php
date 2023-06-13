<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");
require_once(dirname(__DIR__, 1) . "/lib/PageComposer.php");
require_once(dirname(__DIR__) . "/client/PostClient.php");
require_once(dirname(__DIR__) . "/lib/Http/Response.php");

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

        return new Response(status_code: OK, html: $html);
    }
}
