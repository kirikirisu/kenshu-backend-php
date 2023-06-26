<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Response;

class GetSignUpPageHandler implements HandlerInterface
{
    public function __construct(
        public HTMLBuilderInterface $compose)
    {
    }

    public function run(): Response
    {
        $html = $this->compose->signUpPage()->getHtml();

        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
