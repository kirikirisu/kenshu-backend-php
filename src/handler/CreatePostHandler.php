<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");
require_once(dirname(__DIR__, 1) . "/lib/PageComposer.php");
require_once(dirname(__DIR__, 1). "/lib/Http/Request.php");
require_once(dirname(__DIR__) . "/lib/Http/Response.php");
require_once(dirname(__DIR__) . "/lib/Http/Request.php");
require_once(dirname(__DIR__) . "/lib/Validator/ValidatePost.php");

class CreatePostHandler
{
    public function __construct(
        public Request $req,
        public PageComposer $compose,
        public PostClient $post_client)
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

        return new Response(status_code: SEE_OTHER, redirect_url: "http://localhost:8080");
    }

    /**
     * @param PageComposer $compose
     * @param InputError[] $error_list
     */
    public static function createTopPageWithError(PageComposer $compose, PostClient $post_client, array $error_list): Response
    {
        $post_list = $post_client->getPostList();

        $html = $compose->topPage($post_list, $error_list)->getHtml();
        return new Response(status_code: OK, html: $html);
    }
}
