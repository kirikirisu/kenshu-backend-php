<?php

namespace App\Handler;

use App\Repository\PostRepository;
use App\Lib\Errors\InputError;
use App\Lib\HTMLBuilder;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Validator\ValidatePost;
use App\Model\Dto\IndexPostDto;

class CreatePostHandler implements HandlerInterface
{
    public function __construct(
        public Request        $req,
        public HTMLBuilder    $compose,
        public PostRepository $post_client)
    {
    }

    public function run(): Response
    {
        $title = $this->req->post['post-title'];
        $body = $this->req->post['post-body'];

        $error_list = ValidatePost::exec($title, $body);
        if (count($error_list) > 0) return static::createTopPageWithError(compose: $this->compose, post_client: $this->post_client, error_list: $error_list);

        $payload = new IndexPostDto(2, $title, $body, 1);
        $this->post_client->createPost($payload);

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: "http://localhost:8080");
    }

    /**
     * @param HTMLBuilder $compose
     * @param InputError[] $error_list
     */
    public static function createTopPageWithError(HTMLBuilder $compose, PostRepository $post_client, array $error_list): Response
    {
        $post_list = $post_client->getPostList();

        $html = $compose->topPage($post_list, $error_list)->getHtml();
        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
