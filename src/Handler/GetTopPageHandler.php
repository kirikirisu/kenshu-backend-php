<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Response;
use App\Lib\Manager\CsrfManager;
use App\Lib\Manager\SessionManagerInterface;
use App\Repository\PostRepositoryInterface;

class GetTopPageHandler implements HandlerInterface
{
    public function __construct(
        public SessionManagerInterface $session,
        public HTMLBuilderInterface    $compose,
        public PostRepositoryInterface $post_repo)
    {
    }

    public function run(): Response
    {
        $this->session->beginSession();

        $post_list = $this->post_repo->getPostList();
        $html = $this->compose->topPage(data_chunk:  $post_list, csrf_token: CsrfManager::generate($this->session))->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
