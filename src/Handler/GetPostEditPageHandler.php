<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Http\SessionManager;
use App\Lib\Manager\CsrfManager;
use App\Repository\ImageRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\TagRepositoryInterface;

class GetPostEditPageHandler implements HandlerInterface
{
    public function __construct(
        public \PDO                     $pdo,
        public int                      $post_id,
        public Request                  $req,
        public HTMLBuilderInterface     $compose,
        public PostRepositoryInterface  $post_repo,
        public ImageRepositoryInterface $image_repo,
        public TagRepositoryInterface   $tag_repo)
    {
    }

    public function run(): Response
    {
        SessionManager::beginSession();
        $user_id = SessionManager::findValueByKey('user_id');
        if (is_null($user_id)) return new Response(status_code: UNAUTHORIZED_STATUS_CODE, html: "<div>Unauthorized.iii</div>");

        $redirect_url = HOST_BASE_URL . "/?refererStatus=unauthorized";
        $post = $this->post_repo->getPostById(id: $this->post_id);
        if ($post->user_id !== (int)$user_id) return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: $redirect_url);

        try {
            $this->pdo->beginTransaction();
            $post = $this->post_repo->getPostById(id: $this->post_id);
            $image_list = $this->image_repo->getImageListByPostId(post_id: $this->post_id);
            $checked_tag_list = $this->tag_repo->getTagListByPostId(post_id: $this->post_id);
            $tag_list = $this->tag_repo->getTagList();
            $this->pdo->commit();

            $checked_tag_id_list = [];
            foreach ($checked_tag_list as $tag) {
                $checked_tag_id_list[] = $tag->id;
            }

            $html = $this->compose->postEditPage(post: $post, csrf_token: CsrfManager::generate(), image_list: $image_list, tag_list: $tag_list, checked_tag_id_list: $checked_tag_id_list)->getHtml();

            return new Response(status_code: OK_STATUS_CODE, html: $html);

        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            return new Response(status_code: INTERNAL_SERVER_ERROR_STATUS_CODE, html: "<div>サーバーでエラガー発生しました。</div>");
        }
    }
}
