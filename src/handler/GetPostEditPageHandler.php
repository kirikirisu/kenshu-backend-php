<?php
require_once(dirname(__DIR__) . "/lib/Http/Response.php");

class GetPostEditPageHandler
{
    public function __construct(
        public int          $post_id,
        public PageComposer $compose,
        public PostClient   $post_client)
    {

    }

    public function run()
    {
        $post = $this->post_client->getPostById(id: $this->post_id);

        $html = $this->compose->getPostEditPage(post: $post)->getHtml();

        return new Response(status_code: 200, html: $html);
    }
}
