<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Http\SessionManager;
use App\Lib\Manager\CsrfManager;

class GetSignUpPageHandler implements HandlerInterface
{
    public function __construct(
        public Request $req,
        public HTMLBuilderInterface    $compose)
    {
    }

    public function run(): Response
    {
        SessionManager::beginSession();
        if (SessionManager::findValueByKey("user_id")) return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);

        $html = $this->compose->signUpPage(csrf_token: CsrfManager::generate())->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
