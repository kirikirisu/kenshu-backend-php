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

        $redirect_url = "http://localhost:8080/";
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Delete request succeeded', 'redirectUrl' => $redirect_url));
    }
}
