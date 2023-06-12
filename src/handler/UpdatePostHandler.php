<?php
require_once(dirname(__DIR__) . "/lib/Http/Response.php");
require_once(dirname(__DIR__) . "/lib/Http/Request.php");
require_once(dirname(__DIR__) . "/lib/Validator/ValidatePost.php");

class UpdatePostHandler
{

    public function __construct(
        public Request      $req,
        public int          $post_id,
        public PageComposer $compose,
        public PostClient   $post_client)
    {
    }

    public function run(): Response
    {
        $title = $this->req->post['post-title'];
        $body = $this->req->post['post-body'];

        $error_list = ValidatePost::exec(title: $title, body: $body);
        if (count($error_list) > 0) return static::createEditPageWithError(compose: $this->compose, post: new ShowPostDto(id: $this->post_id, user_id: 2, title: $title, body: $body, thumbnail_id: 1), error_list: $error_list);

        $dto = new UpdatePostDto(title: $title, body: $body, thumbnail_id: 1);
        $this->post_client->updatePost($this->post_id, $dto);

        $redirect_url = "http://localhost:8080/posts/" . $this->post_id;
        return new Response(status_code: 303, redirect_url: $redirect_url);
    }

    public static function createEditPageWithError(PageComposer $compose, ShowPostDto $post, array $error_list): Response
    {
        $html = $compose->postEditPage(post: $post, error_list: $error_list)->getHtml();

        return new Response(status_code: 200, html: $html);
    }

}
