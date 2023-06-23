<?php
namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Response;

class SignInUserHandler implements HandlerInterface
{
    public function __construct(
        public HTMLBuilderInterface $compose)
    {
    }

    public function run(): Response
    {
        return new Response(status_code: OK_STATUS_CODE, html: "<div>dummy page</div>");
    }
}
