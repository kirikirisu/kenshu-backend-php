<?php
namespace App\Handler;

use App\Lib\Http\Response;
use App\Lib\PageComposer;
use App\Client\PostClient;

class GetPostEditPageHandler implements HandlerInterface
{
    public function __construct(
        public int          $post_id,
        public PageComposer $compose,
        public PostClient   $post_client)
    {

    }

    public function run(): Response
    {
        $post = $this->post_client->getPostById(id: $this->post_id);

        $html = $this->compose->postEditPage(post: $post)->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
