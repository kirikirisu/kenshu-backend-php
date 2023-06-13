<?php
namespace App\Handler;

require_once(dirname(__DIR__) . "/lib/Http/Response.php");
use App\Lib\Http\Response;

class NotFoundHandler
{
    public function run(): Response
    {
        return new Response(status_code: NOT_FOUND_STATUS_CODE, html: "<h1>Page not found.</h1>");
    }
}
