<?php

namespace App\Handler;

use App\Lib\Http\Response;
use App\Repository\PostRepositoryInterface;

class DeletePostHandler implements HandlerInterface
{
    public function __construct(
        public int            $post_id,
        public PostRepositoryInterface $post_client)
    {
    }

    public function run(): Response
    {
        $this->post_client->deletePost($this->post_id);

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);
    }
}
