<?php

namespace App\Handler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Http\SessionManager;
use App\Repository\PostRepositoryInterface;

class DeletePostHandler implements HandlerInterface
{
    public function __construct(
        public int                     $post_id,
        public Request                 $req,
        public PostRepositoryInterface $post_repo)
    {
    }

    public function run(): Response
    {
        SessionManager::beginSession();
        $user_id = SessionManager::findValueByKey("user_id");

        $post = $this->post_repo->getPostById(id: $this->post_id);
        $redirect_url = HOST_BASE_URL . "?refererStatus=unauthorized";
        if (!$user_id || $post->user_id !== (int)$user_id) return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: $redirect_url);

        $this->post_repo->deletePost($this->post_id);

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);
    }
}
