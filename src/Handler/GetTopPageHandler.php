<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Http\SessionManager;
use App\Lib\Manager\CsrfManager;
use App\Repository\PostRepositoryInterface;

class GetTopPageHandler implements HandlerInterface
{
    public function __construct(
        public Request                 $req,
        public HTMLBuilderInterface    $compose,
        public PostRepositoryInterface $post_repo)
    {
    }

    public function run(): Response
    {
        SessionManager::beginSession();

        $post_list = $this->post_repo->getPostList();
        $html = $this->compose->topPage(data_chunk: $post_list, csrf_token: CsrfManager::generate())->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
