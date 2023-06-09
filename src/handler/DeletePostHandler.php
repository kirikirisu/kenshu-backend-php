<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");

class DeletePostHandler
{
    public function __construct(
        public int $post_id,
        public PostClient $post_client)
    {
    }

    public function run(): void
    {
        $this->post_client->deletePost($this->post_id);

        header("Location: http://localhost:8080", true, 303);
    }
}
