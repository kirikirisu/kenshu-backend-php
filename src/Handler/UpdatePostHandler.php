<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Http\SessionManager;
use App\Lib\Manager\CsrfManager;
use App\Lib\Validator\ValidatePost;
use App\Model\Dto\Post\UpdatePostDto;
use App\Repository\PostRepositoryInterface;

class UpdatePostHandler implements HandlerInterface
{

    public function __construct(
        public Request                 $req,
        public int                     $post_id,
        public HTMLBuilderInterface    $compose,
        public PostRepositoryInterface $post_repo)
    {
    }

    public function run(): Response
    {
        SessionManager::beginSession();
        $user_id = SessionManager::findValueByKey("user_id");
        if (is_null($user_id)) return new Response(status_code: UNAUTHORIZED_STATUS_CODE, html: "<div>Unauthorized</div>");
        if (!CsrfManager::validate(token: $this->req->post['csrf'])) return new Response(status_code: UNAUTHORIZED_STATUS_CODE, html: "<div>エラーが発生しました。</div>");

        $title = $this->req->post['post-title'];
        $body = $this->req->post['post-body'];

        $error_list = ValidatePost::exec(title: $title, body: $body, main_image: "g");
        // TODO
        if (count($error_list) > 0) return new Response(status_code: BAD_REQUEST_STATUS_CODE, html: "<div>更新に失敗しました。</div>");

        $post = $this->post_repo->getPostById(id: $this->post_id);
        if ($post->user_id !== (int)$user_id) return new Response(status_code: UNAUTHORIZED_STATUS_CODE, html: "<div>Unauthorized}</div>");

        // TODO: editable thumbnail, tags, images
        $dto = new UpdatePostDto(title: $title, body: $body);
        $this->post_repo->updatePost($this->post_id, $dto);

        $redirect_url = HOST_BASE_URL . '/posts/' . $this->post_id;
        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: $redirect_url);
    }

}
