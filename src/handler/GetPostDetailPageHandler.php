<?php
require_once(dirname(__DIR__) . "/lib/Http/Response.php");

class GetPostDetailPageHandler
{
    public function __construct(
        public int $post_id,
        public PageComposer $compose,
        public PostClient $post_client)
    {
    }

    public function run(): Response
    {
        $post = $this->post_client->getPostById($this->post_id);

        $html = $this->compose->postDetailPage($post)->getHtml();
        return new Response(status_code: 200, html: $html);
    }
}
