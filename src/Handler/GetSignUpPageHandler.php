<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Response;
use App\Lib\Manager\CsrfManager;
use App\Lib\Manager\SessionManagerInterface;

class GetSignUpPageHandler implements HandlerInterface
{
    public function __construct(
        public SessionManagerInterface $session,
        public HTMLBuilderInterface    $compose)
    {
    }

    public function run(): Response
    {
        $this->session->beginSession();

        $html = $this->compose->signUpPage(csrf_token: CsrfManager::generate($this->session))->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
