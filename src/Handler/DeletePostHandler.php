<?php

namespace App\Handler;

use App\Lib\Http\Response;
use App\Lib\Manager\SessionManagerInterface;
use App\Repository\PostRepositoryInterface;

class DeletePostHandler implements HandlerInterface
{
    public function __construct(
        public int            $post_id,
        public SessionManagerInterface $session,
        public PostRepositoryInterface $post_client)
    {
    }

    public function run(): Response
    {
        $this->session->beginSession();
        $user_id = $this->session->findValueByKey("user_id");
        if (is_null($user_id)) return new Response(status_code: UNAUTHORIZED_STATUS_CODE, html: "<div>Unauthorized</div>");

        $this->post_client->deletePost($this->post_id);

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);
    }
}
