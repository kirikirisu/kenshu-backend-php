<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Manager\CsrfManager;

class SignInUserHandler implements HandlerInterface
{
    public function __construct(
        public Request              $req,
        public HTMLBuilderInterface $compose)
    {
    }

    public function run(): Response
    {
        if (!CsrfManager::validate($this->req->post['csrf'])) return new Response(status_code: OK_STATUS_CODE, html: "<div>エラーが発生しました。</div>");

        return new Response(status_code: OK_STATUS_CODE, html: "<div>dummy page</div>");
    }
}
