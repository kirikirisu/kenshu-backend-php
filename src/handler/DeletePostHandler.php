<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");
require_once(dirname(__DIR__) . "/lib/Http/Response.php");

class DeletePostHandler
{
    public function __construct(
        public int $post_id,
        public PostClient $post_client)
    {
    }

    public function run(): Response
    {
        $this->post_client->deletePost($this->post_id);

        return new Response(status_code: SEE_OTHER, redirect_url: "http://localhost:8080");
    }
}
