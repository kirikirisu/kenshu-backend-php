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
        if ($this->session->findValueByKey("user_id")) return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);

        $html = $this->compose->signInPage(csrf_token: csrfManager::generate($this->session))->getHtml();
        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
