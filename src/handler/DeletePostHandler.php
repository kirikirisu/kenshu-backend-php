<?php
namespace App\Handler;

use App\Client\PostClient;
use App\Lib\Http\Response;

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
