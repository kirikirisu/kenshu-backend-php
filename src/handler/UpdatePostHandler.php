<?php
namespace App\Handler;

use App\Lib\Http\Response;
use App\Lib\Http\Request;
use App\Lib\Validator\ValidatePost;
use App\Model\Dto\UpdatePostDto;
use App\Model\Dto\ShowPostDto;
use App\Lib\PageComposer;
use App\Lib\Errors\InputError;
use App\Client\PostClient;

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
        return new Response(status_code: SEE_OTHER, redirect_url: $redirect_url);
    }

    /**
     * @param PageComposer $compose
     * @param ShowPostDto $post
     * @param InputError[] $error_list
     */
    public static function createEditPageWithError(PageComposer $compose, ShowPostDto $post, array $error_list): Response
    {
        $html = $compose->postEditPage(post: $post, error_list: $error_list)->getHtml();

        return new Response(status_code: OK, html: $html);
    }

}
