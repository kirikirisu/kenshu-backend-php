<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Response;
use App\Repository\ImageRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\TagRepositoryInterface;

class GetPostEditPageHandler implements HandlerInterface
{
    public function __construct(
        public int                      $post_id,
        public HTMLBuilderInterface     $compose,
        public PostRepositoryInterface  $post_repo,
        public ImageRepositoryInterface $image_repo,
        public TagRepositoryInterface   $tag_repo)
    {
    }

    public function run(): Response
    {
        $post = $this->post_repo->getPostById(id: $this->post_id);
        $image_list = $this->image_repo->getImageListByPostId(post_id: $this->post_id);
        $checked_tag_list = $this->tag_repo->getTagListByPostId(post_id: $this->post_id);
        $tag_list = $this->tag_repo->getTagList();

        $checked_tag_id_list = [];
        foreach ($checked_tag_list as $tag) {
            $checked_tag_id_list[] = $tag->id;
        }

        $html = $this->compose->postEditPage(post: $post, image_list: $image_list, tag_list: $tag_list, checked_tag_id_list: $checked_tag_id_list)->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
