<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Response;
use App\Lib\Manager\CsrfManager;
use App\Lib\Manager\SessionManagerInterface;

class GetSignInPageHandler implements HandlerInterface
{
    public function __construct(
        public SessionManagerInterface $session,
        public HTMLBuilderInterface $compose)
    {
    }

    public function run(): Response
    {
        $this->session->beginSession();

        $html = $this->compose->signInPage(csrf_token: csrfManager::generate($this->session))->getHtml();
        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
