<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Http\SessionManager;
use App\Lib\Manager\CsrfManager;
use App\Model\Dto\Tag\PostTagListDto;
use App\Repository\PostRepositoryInterface;
use App\Repository\TagRepositoryInterface;
use App\Repository\UserRepositoryInterface;

class GetTopPageHandler implements HandlerInterface
{
    public function __construct(
        public Request                 $req,
        public HTMLBuilderInterface    $compose,
        public UserRepositoryInterface $user_repo,
        public TagRepositoryInterface  $tag_repo,
        public PostRepositoryInterface $post_repo)
    {
    }

    public function run(): Response
    {
        SessionManager::beginSession();

        $post_list = $this->post_repo->getPostList();

        $post_id_list = [];
        foreach ($post_list as $post) {
            $post_id_list[] = $post->id;
        }
        $post_tag_list = $this->tag_repo->getPostTagByPostIdList($post_id_list);

        /** @var array<string, PostTagListDto> $post_tag_hash_map */
        $post_tag_hash_map = [];
        foreach ($post_tag_list as $post_tag) {
            $post_tag_hash_map[$post_tag->post_id] = $post_tag;
        }

        $html = $this->compose->topPage(post_list: $post_list, post_tag_hash_map: $post_tag_hash_map, csrf_token: CsrfManager::generate())->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
